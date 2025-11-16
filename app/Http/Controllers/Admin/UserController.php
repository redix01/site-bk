<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('wallet')->latest()->paginate(10);
        return inertia('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return inertia('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        if ($request->filled('passport_expiry')) {
            $rawExpiry = (string) $request->input('passport_expiry');
            if (preg_match('/^\d{2}[\/\-]\d{2}[\/\-]\d{4}$/', $rawExpiry)) {
                $normalized = str_replace('-', '/', $rawExpiry);
                $formats = ['d/m/Y', 'm/d/Y'];
                foreach ($formats as $format) {
                    try {
                        $parsedExpiry = Carbon::createFromFormat($format, $normalized);
                        if ($parsedExpiry !== false) {
                            $request->merge(['passport_expiry' => $parsedExpiry->format('Y-m-d')]);
                            break;
                        }
                    } catch (\Throwable $e) {
                        // Ignore and try next format.
                    }
                }
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'nullable|string|in:male,female,other,prefer_not_to_say',
            'nationality' => 'required|string|max:120',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:120',
            'passport_number' => 'required|string|max:64',
            'passport_country' => 'required|string|max:120',
            'passport_expiry' => 'nullable|date|after_or_equal:today',
            'tax_identification_number' => 'nullable|string|max:120',
            'occupation' => 'nullable|string|max:120',
            'employment_status' => 'nullable|string|max:120',
            'source_of_funds' => 'nullable|string|max:160',
            'branch_code' => 'nullable|string|max:64',
            'preferred_currency' => 'required|string|size:3',
            'balance' => 'nullable|numeric|min:0',
            'is_admin' => 'boolean',
            'account_type' => 'required|in:savings,current,business',
        ]);

        $openingBalance = isset($validated['balance'])
            ? (int) round(((float) $validated['balance']) * 100)
            : 0;
        $preferredCurrency = strtoupper($validated['preferred_currency'] ?? 'USD');
        $isAdmin = $request->boolean('is_admin');

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'pass_preview' => $validated['password'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'] ?? null,
            'passport_number' => $validated['passport_number'] ?? null,
            'passport_country' => $validated['passport_country'] ?? null,
            'passport_expiry' => $validated['passport_expiry'] ?? null,
            'tax_identification_number' => $validated['tax_identification_number'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'employment_status' => $validated['employment_status'] ?? null,
            'source_of_funds' => $validated['source_of_funds'] ?? null,
            'branch_code' => $validated['branch_code'] ?? null,
            'preferred_currency' => $preferredCurrency,
            'account_type' => $validated['account_type'],
            'status' => 'active',
            'balance' => $openingBalance,
            'is_admin' => $isAdmin,
        ];

        if (Schema::hasColumn('users', 'role')) {
            $userData['role'] = $isAdmin ? 'admin' : 'customer';
        }

        $user = User::create($userData);

        // Create wallet with account number for the user
        Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => $openingBalance,
            'ledger_balance' => $openingBalance,
            'currency' => $preferredCurrency,
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $wallet = $this->ensureWallet($user);

        $user->load('wallet');

        $recentTransactions = Transaction::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function (Transaction $transaction) {
                return [
                    'id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'recipient_id' => $transaction->recipient_id,
                    'type' => $transaction->type,
                    'amount' => (int) $transaction->amount,
                    'status' => $transaction->status,
                    'reference' => $transaction->reference,
                    'description' => $transaction->description,
                    'created_at' => optional($transaction->created_at)->toIso8601String(),
                ];
            });

        $loginHistoryRecords = LoginHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $loginHistory = $loginHistoryRecords->map(function (LoginHistory $entry) {
            return [
                'id' => $entry->id,
                'ip_address' => $entry->ip_address,
                'device' => $entry->device,
                'platform' => $entry->platform,
                'browser' => $entry->browser,
                'location' => $entry->location,
                'login_successful' => (bool) $entry->login_successful,
                'formatted_created_at' => $entry->formatted_created_at,
                'created_at' => optional($entry->created_at)->toIso8601String(),
            ];
        });

        $stats = [
            'totalDeposits' => (int) Transaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->where('status', 'completed')
                ->sum('amount'),
            'totalWithdrawals' => (int) Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->sum('amount'),
            'totalTransfersSent' => (int) Transaction::where('user_id', $user->id)
                ->where('type', 'transfer')
                ->sum('amount'),
            'totalTransfersReceived' => (int) Transaction::where('recipient_id', $user->id)
                ->where('type', 'transfer')
                ->sum('amount'),
            'pendingTransactions' => Transaction::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('recipient_id', $user->id);
                })
                ->where('status', 'pending')
                ->count(),
        ];

        $security = [
            'hasTransactionPin' => (bool) $user->has_transaction_pin,
            'emailVerified' => !is_null($user->email_verified_at),
            'failedLogins' => LoginHistory::where('user_id', $user->id)->failed()->count(),
            'successfulLogins' => LoginHistory::where('user_id', $user->id)->successful()->count(),
            'lastLoginAt' => optional($loginHistoryRecords->first()?->created_at)->toIso8601String(),
        ];

        $walletData = $wallet ? [
            'id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'account_number' => $wallet->account_number,
            'balance' => (int) $wallet->balance,
            'ledger_balance' => (int) $wallet->ledger_balance,
            'currency' => $wallet->currency,
            'status' => $wallet->status,
            'created_at' => optional($wallet->created_at)->toIso8601String(),
            'updated_at' => optional($wallet->updated_at)->toIso8601String(),
        ] : null;

        return inertia('Admin/Users/Show', [
            'user' => $user->load('wallet'),
            'wallet' => $walletData,
            'recentTransactions' => $recentTransactions,
            'loginHistory' => $loginHistory,
            'stats' => $stats,
            'security' => $security,
            'supportedCurrencies' => config('banking.supported_currencies', ['USD']),
            // Expose the transaction PIN only in the admin view
            'transactionPin' => $user->getRawOriginal('transaction_pin'),
        ]);
    }

    public function edit(User $user)
    {
        $user->load('wallet');
        return inertia('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'balance' => 'nullable|numeric|min:0',
            'is_admin' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update user fields
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->boolean('is_admin'),
            ]);

            // Update balance in wallet if provided (allow 0)
            if ($request->has('balance') && $request->balance !== null && $request->balance !== '') {
                $wallet = $this->ensureWallet($user);
                $balanceInDollars = (float) $request->balance;
                $balanceInCents = (int) round($balanceInDollars * 100);
                
                // Update wallet balance (stored as decimal dollars)
                $wallet->update([
                    'balance' => $balanceInDollars,
                    'ledger_balance' => $balanceInDollars,
                ]);

                // Also update user balance field for backwards compatibility (stored in cents as integer)
                $user->update([
                    'balance' => $balanceInCents,
                ]);
            }
        });

        AuditLog::logEvent('user.updated', [
            'user_id' => $user->id,
            'updated_fields' => array_filter([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->boolean('is_admin'),
                'balance' => $request->has('balance') ? $request->balance : null,
            ]),
        ], auth()->user());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function fund(Request $request, User $user)
    {
        $payload = $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
            'notify_user' => 'sometimes|boolean',
        ]);

        $amountInCents = (int) round($payload['amount'] * 100);

        if ($amountInCents <= 0) {
            return back()->with('error', 'Please provide a valid amount to fund.');
        }

        $reference = !empty($payload['reference'])
            ? Str::upper($payload['reference'])
            : 'FND-' . Str::upper(Str::random(10));

        DB::transaction(function () use ($user, $amountInCents, $payload, $reference) {
            $wallet = $this->ensureWallet($user);
            $previousBalance = (int) $wallet->balance;

            $wallet = $wallet->credit($amountInCents);
            $newBalance = (int) $wallet->balance;

            $user->update([
                'balance' => ($user->balance ?? 0) + $amountInCents,
            ]);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $amountInCents,
                'fee' => 0,
                'reference' => $reference,
                'status' => 'completed',
                'description' => $payload['description'] ?: 'Manual funding by admin',
                'metadata' => [
                    'source' => 'admin_funding',
                    'admin_id' => auth()->id(),
                    'previous_balance' => $previousBalance,
                    'new_balance' => $newBalance,
                    'notes' => $payload['description'] ?? null,
                    'notify_user' => (bool) ($payload['notify_user'] ?? false),
                ],
            ]);

            AuditLog::logEvent('user.balance_funded', [
                'amount' => $amountInCents,
                'reference' => $reference,
                'transaction_id' => $transaction->id,
            ], $user);
        });

        return back()->with('success', 'User balance funded successfully.');
    }

    /**
     * Update the preferred currency for a user and synchronize their wallet.
     */
    public function updateCurrency(Request $request, User $user)
    {
        $supportedCurrencies = array_map('strtoupper', config('banking.supported_currencies', []));

        $request->merge([
            'preferred_currency' => strtoupper((string) $request->input('preferred_currency')),
        ]);

        $validated = $request->validate([
            'preferred_currency' => [
                'required',
                'string',
                'size:3',
                Rule::in($supportedCurrencies),
            ],
        ]);

        $currency = $validated['preferred_currency'];

        $user->update([
            'preferred_currency' => $currency,
        ]);

        $wallet = $this->ensureWallet($user);
        $wallet->update([
            'currency' => $currency,
        ]);

        AuditLog::logEvent('user.currency_updated', [
            'currency' => $currency,
            'admin_id' => auth()->id(),
        ], $user);

        return back()->with('success', 'Preferred currency updated successfully.');
    }

    /**
     * Update the account created date for a user.
     */
    public function updateCreatedAt(Request $request, User $user)
    {
        $validated = $request->validate([
            'created_at' => 'required|date',
        ]);

        $oldCreatedAt = $user->created_at;
        $newCreatedAt = Carbon::parse($validated['created_at']);

        // Update using DB::table to bypass mass assignment protection for created_at
        DB::table('users')
            ->where('id', $user->id)
            ->update(['created_at' => $newCreatedAt]);

        // Refresh the model to get the updated created_at
        $user->refresh();

        AuditLog::logEvent('user.created_at_updated', [
            'user_id' => $user->id,
            'old_created_at' => $oldCreatedAt?->toIso8601String(),
            'new_created_at' => $newCreatedAt->toIso8601String(),
        ], auth()->user());

        return back()->with('success', 'Account created date updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Suspend a user account.
     */
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->suspend($request->reason);

        return back()->with('success', 'User account suspended successfully.');
    }

    /**
     * Activate a user account.
     */
    public function activate(User $user)
    {
        $user->activate();

        return back()->with('success', 'User account activated successfully.');
    }

    /**
     * Lock a user account.
     */
    public function lock(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->lock($request->reason);

        return back()->with('success', 'User account locked successfully.');
    }

    /**
     * Impersonate a user (login as that user).
     */
    public function impersonate(Request $request, User $user)
    {
        // Log the impersonation action
        AuditLog::logEvent('user.impersonated', [
            'impersonated_user_id' => $user->id,
            'impersonated_user_email' => $user->email,
            'admin_id' => auth()->id(),
            'admin_email' => auth()->user()->email,
        ], $user);

        // Log out the current admin
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log in as the target user
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect to user dashboard
        return redirect()->route('dashboard');
    }

    protected function ensureWallet(User $user): Wallet
    {
        if ($user->wallet) {
            return $user->wallet;
        }

        $preferredCurrency = strtoupper($user->preferred_currency ?? 'USD');

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => $user->balance ?? 0,
            'ledger_balance' => $user->balance ?? 0,
            'currency' => $preferredCurrency,
            'status' => 'active',
        ]);

        $user->setRelation('wallet', $wallet);

        return $wallet;
    }
}
