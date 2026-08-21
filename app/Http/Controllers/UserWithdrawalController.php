<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Illuminate\Support\Str;

class UserWithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        return inertia('Withdraw', [
            'wallet' => $wallet,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'method' => 'required|string|in:bank_transfer,paypal,crypto,check',
            'account_details' => 'required|string|max:1000',
        ]);
        
        $amountInCents = (int) ($validated['amount'] * 100);
        
        // Check if user has sufficient balance
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < $amountInCents) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }
        
        // Create pending withdrawal transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amountInCents,
            'fee' => 0,
            'reference' => 'WDL-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'description' => 'Withdrawal via ' . $validated['method'],
            'metadata' => [
                'method' => $validated['method'],
                'account_details' => $validated['account_details'],
            ],
        ]);
        
        // Debit wallet (funds will be held until admin approves)
        $wallet->debit($amountInCents);

        // Notify Admin
        try {
            $adminEmail = \App\Support\SettingsManager::get('site_email', config('mail.from.address'));
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->queue(new \App\Mail\AdminAlertMail(
                    'New Withdrawal Request',
                    "User {$user->name} has requested a withdrawal of " . number_format($validated['amount'], 2) . " via {$validated['method']}.",
                    'warning',
                    $transaction
                ));
            }
        } catch (\Exception $e) {
            report($e);
        }

        // Notify User
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\TransactionStatusMail($transaction, $user->name));
        } catch (\Exception $e) {
            report($e);
        }
        
        return redirect()->route('transactions')->with('success', 'Withdrawal request submitted! An admin will process it shortly.');
    }
}

