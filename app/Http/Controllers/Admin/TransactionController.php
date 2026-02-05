<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);

        return inertia('Admin/Transactions/Index', [
            'transactions' => $transactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::with('wallet')->get();
        return inertia('Admin/Transactions/Create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->normalizeMoneyInputs($request, ['amount']);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $data = $request->all();
        $data['amount'] = (int) round(((float) $data['amount']) * 100); // Convert to kobo
        $data['reference'] = 'TXN-' . time() . '-' . rand(1000, 9999);
        $data['metadata'] = $this->withPendingFundsHeldMetadata($data);

        $transaction = Transaction::create($data);

        if ($transaction->status === 'completed') {
            $transaction->loadMissing('user');
            $this->applyApprovalEffects($transaction);
        }

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('user');
        return inertia('Admin/Transactions/Show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $users = User::all();
        return inertia('Admin/Transactions/Edit', [
            'transaction' => $transaction,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->normalizeMoneyInputs($request, ['amount']);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:deposit,withdrawal,transfer',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $previousStatus = $transaction->status;
        $data = $request->all();
        $data['amount'] = (int) round(((float) $data['amount']) * 100); // Convert to kobo
        $data['metadata'] = $this->withPendingFundsHeldMetadata($data, $transaction->metadata ?? []);

        $transaction->update($data);

        if ($previousStatus !== 'completed' && $transaction->status === 'completed') {
            $transaction->loadMissing('user');
            $this->applyApprovalEffects($transaction);
        }

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
                        ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Approve a pending transaction.
     */
    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be approved.');
        }

        $transaction->loadMissing('user');
        $this->applyApprovalEffects($transaction);

        $transaction->update([
            'status' => 'completed',
            'metadata' => array_merge($transaction->metadata ?? [], [
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]),
        ]);

        \App\Models\AuditLog::logEvent('transaction.approved', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
        ], $transaction);

        return back()->with('success', 'Transaction approved successfully.');
    }

    /**
     * Reject a pending transaction.
     */
    public function reject(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be rejected.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $transaction->loadMissing('user');
        $this->applyRejectionEffects($transaction);

        $transaction->update([
            'status' => 'failed',
            'metadata' => array_merge($transaction->metadata ?? [], [
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->reason,
            ]),
        ]);

        \App\Models\AuditLog::logEvent('transaction.rejected', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'reason' => $request->reason,
        ], $transaction);

        return back()->with('success', 'Transaction rejected successfully.');
    }

    /**
     * Reverse a completed transaction.
     */
    public function reverse(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Only completed transactions can be reversed.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $transaction->loadMissing('user');
        try {
            $this->applyReversalEffects($transaction);
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to reverse transaction: ' . $e->getMessage());
        }

        // Create reversal transaction
        $reversal = Transaction::create([
            'user_id' => $transaction->user_id,
            'recipient_id' => $transaction->recipient_id,
            'type' => 'refund',
            'amount' => -$transaction->amount,
            'reference' => 'REV-TXN-' . time() . '-' . str_pad($transaction->id, 3, '0', STR_PAD_LEFT),
            'status' => 'completed',
            'description' => 'Reversal: ' . $transaction->description,
            'metadata' => [
                'original_transaction_id' => $transaction->id,
                'original_transaction_type' => $transaction->type,
                'reversed_by' => auth()->id(),
                'reversal_reason' => $request->reason,
            ],
        ]);

        $transaction->update([
            'status' => 'reversed',
            'metadata' => array_merge($transaction->metadata ?? [], [
                'reversed_by' => auth()->id(),
                'reversed_at' => now(),
                'reversal_reason' => $request->reason,
                'reversal_transaction_id' => $reversal->id,
            ]),
        ]);

        \App\Models\AuditLog::logEvent('transaction.reversed', [
            'transaction_id' => $transaction->id,
            'reversal_id' => $reversal->id,
            'reason' => $request->reason,
        ], $transaction);

        return back()->with('success', 'Transaction reversed successfully.');
    }

    private function applyApprovalEffects(Transaction $transaction): void
    {
        $wallet = $this->getWalletForUser($transaction->user);
        $metadata = $transaction->metadata ?? [];
        $transferType = $metadata['transfer_type'] ?? null;

        if ($transaction->type === 'deposit') {
            $wallet->credit($transaction->amount);
            $this->syncLegacyUserBalance($transaction->user, $wallet);
            return;
        }

        if ($transaction->type === 'withdrawal') {
            if (!$this->fundsWereHeld($metadata)) {
                $wallet->debit($transaction->amount);
                $this->syncLegacyUserBalance($transaction->user, $wallet);
            }
            return;
        }

        if ($transaction->type === 'transfer' && $transferType === 'wire') {
            if (!$this->fundsWereHeld($metadata)) {
                $totalAmount = $transaction->amount + ($transaction->fee ?? 0);
                $wallet->debit($totalAmount);
                $this->syncLegacyUserBalance($transaction->user, $wallet);
            }
            return;
        }
    }

    private function applyRejectionEffects(Transaction $transaction): void
    {
        $wallet = $this->getWalletForUser($transaction->user);
        $metadata = $transaction->metadata ?? [];
        $transferType = $metadata['transfer_type'] ?? null;

        if ($transaction->type === 'withdrawal') {
            if ($this->fundsWereHeld($metadata)) {
                $wallet->credit($transaction->amount);
                $this->syncLegacyUserBalance($transaction->user, $wallet);
            }
            return;
        }

        if ($transaction->type === 'transfer' && $transferType === 'wire') {
            if ($this->fundsWereHeld($metadata)) {
                $totalAmount = $transaction->amount + ($transaction->fee ?? 0);
                $wallet->credit($totalAmount);
                $this->syncLegacyUserBalance($transaction->user, $wallet);
            }
            return;
        }
    }

    private function applyReversalEffects(Transaction $transaction): void
    {
        $wallet = $this->getWalletForUser($transaction->user);

        if ($transaction->type === 'deposit') {
            $wallet->debit($transaction->amount);
            $this->syncLegacyUserBalance($transaction->user, $wallet);
            return;
        }

        if ($transaction->type === 'withdrawal') {
            $wallet->credit($transaction->amount);
            $this->syncLegacyUserBalance($transaction->user, $wallet);
            return;
        }
    }

    private function getWalletForUser(User $user): Wallet
    {
        $user->loadMissing('wallet');

        if ($user->wallet) {
            return $user->wallet;
        }

        $preferredCurrency = strtoupper($user->preferred_currency ?: 'USD');

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 0,
            'ledger_balance' => 0,
            'currency' => $preferredCurrency,
            'status' => 'active',
        ]);

        $user->setRelation('wallet', $wallet);

        return $wallet;
    }

    private function syncLegacyUserBalance(User $user, Wallet $wallet): void
    {
        $wallet->refresh();
        $user->update([
            'balance' => (int) round(((float) $wallet->balance) * 100),
        ]);
    }

    private function fundsWereHeld(array $metadata): bool
    {
        if (array_key_exists('funds_held', $metadata)) {
            return (bool) $metadata['funds_held'];
        }

        return true;
    }

    private function withPendingFundsHeldMetadata(array $data, ?array $existing = null): ?array
    {
        $metadata = $existing ?? ($data['metadata'] ?? null);
        if ($metadata !== null && !is_array($metadata)) {
            $metadata = null;
        }

        $status = $data['status'] ?? null;
        $type = $data['type'] ?? null;

        if ($status === 'pending' && in_array($type, ['withdrawal', 'transfer'], true)) {
            $metadata = $metadata ?? [];
            if (!array_key_exists('funds_held', $metadata)) {
                $metadata['funds_held'] = false;
            }
        }

        return $metadata;
    }

    private function normalizeMoneyInputs(Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            if (!$request->has($field)) {
                continue;
            }

            $value = $request->input($field);
            if (!is_string($value)) {
                continue;
            }

            $normalized = str_replace([',', ' '], '', $value);
            $request->merge([$field => $normalized]);
        }
    }
}
