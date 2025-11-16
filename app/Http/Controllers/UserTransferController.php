<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionCode;
use App\Models\Wallet;
use App\Models\AuditLog;
use App\Mail\AdminTransferReviewMail;
use App\Mail\TransferCodeRequestMail;
use App\Mail\TransferReceivedMail;
use App\Mail\TransferSentMail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserTransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        return inertia('Transfer', [
            'wallet' => $wallet,
        ]);
    }

    public function storeInternal(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isLocked()) {
            AuditLog::logEvent('transfer.blocked', [
                'reason' => 'account_locked',
                'transfer_type' => 'internal',
            ], $user);
            
            return redirect()->back()->withErrors([
                'error' => 'Your account is locked. Transfers are disabled. Please contact support for assistance.',
            ]);
        }
        
        $validated = $request->validate([
            'recipient_account' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_pin' => 'required|string|size:6',
            'transaction_code' => 'required|string',
        ]);
        
        // Verify transaction PIN
        if (!$user->transaction_pin) {
            return redirect()->back()->withErrors(['error' => 'Transaction PIN not set. Please contact support.']);
        }
        
        if ($validated['transaction_pin'] !== (string) $user->transaction_pin) {
            AuditLog::logEvent('transfer.failed', [
                'reason' => 'Invalid transaction PIN',
                'amount' => $validated['amount'],
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Invalid transaction PIN']);
        }
        
        // Verify transaction code
        $transactionCode = TransactionCode::where('code', strtoupper($validated['transaction_code']))
            ->where('is_used', false)
            ->where('type', 'transfer')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$transactionCode) {
            AuditLog::logEvent('transfer.failed', [
                'reason' => 'Invalid or expired transaction code',
                'code' => $validated['transaction_code'],
                'amount' => $validated['amount'],
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Invalid or expired transaction code']);
        }
        
        // Check if code has specific amount requirement
        $amountInCents = (int) ($validated['amount'] * 100);
        if ($transactionCode->amount !== null && $transactionCode->amount != $amountInCents) {
            return redirect()->back()->withErrors(['error' => 'This code is for a fixed amount of $' . number_format($transactionCode->amount / 100, 2)]);
        }
        
        // If code has a specific amount, use it
        if ($transactionCode->amount !== null) {
            $amountInCents = $transactionCode->amount;
        }
        
        // Find recipient by account number
        $recipientWallet = Wallet::where('account_number', $validated['recipient_account'])->first();
        
        if (!$recipientWallet) {
            return redirect()->back()->withErrors(['error' => 'Recipient account not found']);
        }
        
        $recipient = $recipientWallet->user;
        
        if ($recipient->id === $user->id) {
            return redirect()->back()->withErrors(['error' => 'You cannot transfer to yourself']);
        }
        
        // Check if user has sufficient balance
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < $amountInCents) {
            return redirect()->back()->withErrors(['error' => 'Insufficient balance']);
        }
        
        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'recipient_id' => $recipient->id,
            'type' => 'transfer',
            'amount' => $amountInCents,
            'fee' => 0,
            'reference' => 'INT-' . strtoupper(Str::random(10)),
            'status' => 'completed',
            'description' => $validated['description'] ?? 'Internal transfer to ' . $recipient->name,
        ]);
        
        // Mark transaction code as used
        $transactionCode->update([
            'is_used' => true,
            'used_by' => $user->id,
            'used_at' => now(),
            'transaction_id' => $transaction->id,
        ]);
        
        // Update balances
        $wallet->debit($amountInCents);
        $recipientWallet->credit($amountInCents);
        
        // Refresh wallet instances to get updated balances
        $wallet->refresh();
        $recipientWallet->refresh();
        
        // Send email notifications to both sender and recipient
        Mail::to($user->email)->queue(new TransferSentMail(
            $transaction,
            $recipient->name,
            $recipientWallet->account_number,
            $wallet->balance
        ));
        
        Mail::to($recipient->email)->queue(new TransferReceivedMail(
            $transaction,
            $user->name,
            $wallet->account_number,
            $recipientWallet->balance
        ));
        
        // Log the event
        AuditLog::logEvent('transfer.completed', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'amount' => $amountInCents,
            'recipient_id' => $recipient->id,
            'sender_new_balance' => $wallet->balance,
            'recipient_new_balance' => $recipientWallet->balance,
        ], $transaction);
        
        return redirect()->route('transfer.success', $transaction->id);
    }
    
    public function storeWire(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isLocked()) {
            AuditLog::logEvent('transfer.blocked', [
                'reason' => 'account_locked',
                'transfer_type' => 'wire',
            ], $user);
            
            return redirect()->back()->withErrors([
                'error' => 'Your account is locked. Transfers are disabled. Please contact support for assistance.',
            ]);
        }
        
        $validated = $request->validate([
            'beneficiary_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'routing_number' => 'required|string|max:50',
            'swift_code' => 'nullable|string|max:11',
            'beneficiary_address' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_pin' => 'required|string|size:6',
            'transaction_code' => 'required|string',
        ]);
        
        // Verify transaction PIN
        if (!$user->transaction_pin) {
            return redirect()->back()->withErrors(['error' => 'Transaction PIN not set. Please contact support.']);
        }
        
        if ($validated['transaction_pin'] !== (string) $user->transaction_pin) {
            AuditLog::logEvent('wire_transfer.failed', [
                'reason' => 'Invalid transaction PIN',
                'amount' => $validated['amount'],
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Invalid transaction PIN']);
        }
        
        // Verify transaction code
        $transactionCode = TransactionCode::where('code', strtoupper($validated['transaction_code']))
            ->where('is_used', false)
            ->where('type', 'transfer')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$transactionCode) {
            AuditLog::logEvent('wire_transfer.failed', [
                'reason' => 'Invalid or expired transaction code',
                'code' => $validated['transaction_code'],
                'amount' => $validated['amount'],
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Invalid or expired transaction code']);
        }
        
        $amountInCents = (int) ($validated['amount'] * 100);
        
        // Check if code has specific amount requirement
        if ($transactionCode->amount !== null && $transactionCode->amount != $amountInCents) {
            return redirect()->back()->withErrors(['error' => 'This code is for a fixed amount of $' . number_format($transactionCode->amount / 100, 2)]);
        }
        
        // If code has a specific amount, use it
        if ($transactionCode->amount !== null) {
            $amountInCents = $transactionCode->amount;
        }
        
        $wireFee = (int) ($amountInCents * 0.02); // 2% wire transfer fee
        $totalAmount = $amountInCents + $wireFee;
        
        // Check if user has sufficient balance
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < $totalAmount) {
            return redirect()->back()->withErrors(['error' => 'Insufficient balance (including wire transfer fee)']);
        }
        
        // Create pending wire transfer transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'transfer',
            'amount' => $amountInCents,
            'fee' => $wireFee,
            'reference' => 'WIRE-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'description' => $validated['description'] ?? 'Wire transfer to ' . $validated['beneficiary_name'],
            'metadata' => [
                'transfer_type' => 'wire',
                'beneficiary_name' => $validated['beneficiary_name'],
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'routing_number' => $validated['routing_number'],
                'swift_code' => $validated['swift_code'],
                'beneficiary_address' => $validated['beneficiary_address'],
            ],
        ]);
        
        // Mark transaction code as used
        $transactionCode->update([
            'is_used' => true,
            'used_by' => $user->id,
            'used_at' => now(),
            'transaction_id' => $transaction->id,
        ]);
        
        // Debit wallet (funds will be held until admin approves wire transfer)
        $wallet->debit($totalAmount);
        
        // Refresh wallet to get updated balance
        $wallet->refresh();
        
        // Send email notification to sender
        Mail::to($user->email)->queue(new TransferSentMail(
            $transaction,
            $validated['beneficiary_name'],
            $validated['account_number'],
            $wallet->balance
        ));
        
        // Log the event
        AuditLog::logEvent('wire_transfer.initiated', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'amount' => $amountInCents,
            'fee' => $wireFee,
            'sender_new_balance' => $wallet->balance,
        ], $transaction);
        
        return redirect()->route('transfer.success', $transaction->id);
    }
    
    public function requestTransferCode(Request $request)
    {
        $user = Auth::user();
        $supportEmail = env('MAIL_SUPPORT');

        if ($user->isLocked()) {
            AuditLog::logEvent('transfer.blocked', [
                'reason' => 'account_locked',
                'transfer_type' => $request->input('transfer_type'),
            ], $user);

            return response()->json([
                'message' => 'Your account is locked. Transfers are disabled. Please contact support for assistance.',
            ], 423);
        }

        if (blank($supportEmail)) {
            return response()->json([
                'message' => 'Support email is not configured. Please contact an administrator.',
            ], 422);
        }

        $validated = $request->validate([
            'transfer_type' => ['required', Rule::in(['internal', 'wire'])],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:500'],
            'recipient_account' => ['nullable', 'string', 'max:50', 'required_if:transfer_type,internal'],
            'wire_bank_name' => ['nullable', 'string', 'max:255', 'required_if:transfer_type,wire'],
            'wire_account_number' => ['nullable', 'string', 'max:50', 'required_if:transfer_type,wire'],
            'wire_routing_number' => ['nullable', 'string', 'max:50', 'required_if:transfer_type,wire'],
            'wire_swift_code' => ['nullable', 'string', 'max:11'],
            'wire_beneficiary_name' => ['nullable', 'string', 'max:255', 'required_if:transfer_type,wire'],
            'wire_beneficiary_address' => ['nullable', 'string', 'max:500', 'required_if:transfer_type,wire'],
        ]);

        $wallet = $user->wallet;

        $details = array_merge($validated, [
            'currency' => $wallet?->currency ?? 'USD',
            'account_number' => $wallet?->account_number,
        ]);

        Mail::to($supportEmail)->send(new TransferCodeRequestMail($user, $details));

        AuditLog::logEvent('transfer.code_requested', [
            'transfer_type' => $validated['transfer_type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
        ], $user);

        return response()->json([
            'message' => 'Transfer code request sent to support.',
        ]);
    }

    public function success($transactionId)
    {
        $user = Auth::user();
        
        $transaction = Transaction::with(['recipient.wallet', 'user.wallet'])
            ->where('id', $transactionId)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $transaction->makeVisible('metadata');

        $metadata = $transaction->metadata ?? [];

        if ($transaction->status === 'pending' && empty($metadata['admin_review_notified_at'])) {
            $supportEmail = env('MAIL_SUPPORT');

            if (!blank($supportEmail)) {
                Mail::to($supportEmail)->send(new AdminTransferReviewMail($transaction));
                $metadata['admin_review_notified_at'] = now()->toIso8601String();
                $transaction->metadata = $metadata;
                $transaction->save();
            }
        }
        
        // Add recipient info for internal transfers
        if ($transaction->recipient) {
            $transaction->recipient = [
                'name' => $transaction->recipient->name,
                'account_number' => $transaction->recipient->wallet->account_number ?? 'N/A',
            ];
        }
        
        return inertia('TransferSuccess', [
            'transaction' => $transaction,
        ]);
    }
    
    public function downloadReceipt($transactionId)
    {
        $user = Auth::user();
        
        $transaction = Transaction::with(['recipient.wallet', 'user.wallet'])
            ->where('id', $transactionId)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $pdf = Pdf::loadView('pdf.transaction-receipt', [
            'transaction' => $transaction,
            'user' => $user,
        ]);
        
        return $pdf->download('transaction-' . $transaction->reference . '.pdf');
    }
}
