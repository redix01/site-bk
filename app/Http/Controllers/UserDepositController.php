<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionCode;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserDepositController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        
        // Fetch enabled payment methods from database
        $paymentMethods = PaymentMethod::enabled()->ordered()->get();
        
        // Format payment methods for frontend
        $depositMethods = [];
        foreach ($paymentMethods as $method) {
            $config = $method->getFormattedConfig();
            
            // Replace placeholders in instructions with user's actual data
            if (isset($config['instructions']) && is_array($config['instructions'])) {
                foreach ($config['instructions'] as $key => $value) {
                    if ($value === '{{USER_NAME}}') {
                        $config['instructions'][$key] = $user->name;
                    } elseif ($value === '{{ACCOUNT_NUMBER}}') {
                        $config['instructions'][$key] = $wallet ? $wallet->account_number : 'N/A';
                    }
                }
            }
            
            $depositMethods[$method->key] = $config;
        }
        
        return inertia('Deposit', [
            'wallet' => $wallet,
            'depositMethods' => $depositMethods,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $depositType = $request->input('deposit_type', 'code'); // 'code' or 'payment'
        
        if ($depositType === 'code') {
            return $this->storeCodeDeposit($request, $user);
        } else {
            return $this->storePaymentDeposit($request, $user);
        }
    }
    
    /**
     * Handle deposit using transaction code (admin-issued, instant)
     */
    private function storeCodeDeposit(Request $request, $user)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);
        
        // Find the transaction code
        $transactionCode = TransactionCode::where('code', strtoupper($validated['code']))
            ->where('is_used', false)
            ->where('type', 'deposit')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$transactionCode) {
            return redirect()->back()->with('error', 'Invalid or expired transaction code');
        }
        
        // Check if amount matches (if code has specific amount)
        $amountInCents = (int) ($validated['amount'] * 100);
        
        // If code has a specific amount, it must match
        if ($transactionCode->amount !== null && $transactionCode->amount !== $amountInCents) {
            return redirect()->back()->with('error', 'This code is for a fixed amount of $' . number_format($transactionCode->amount / 100, 2));
        }
        
        // If code doesn't have a specific amount, use the user-entered amount
        if ($transactionCode->amount !== null) {
            $amountInCents = $transactionCode->amount;
        }
        
        // Create completed transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amountInCents,
            'fee' => 0,
            'reference' => 'DEP-' . strtoupper(Str::random(10)),
            'status' => 'completed',
            'description' => 'Deposit using code: ' . $transactionCode->code,
            'metadata' => [
                'deposit_type' => 'code',
                'code' => $transactionCode->code,
            ],
        ]);
        
        // Update wallet balance immediately
        $wallet = $user->wallet;
        if ($wallet) {
            $wallet->credit($amountInCents);
        }
        
        // Mark code as used
        $transactionCode->update([
            'is_used' => true,
            'used_by' => $user->id,
            'used_at' => now(),
            'transaction_id' => $transaction->id,
        ]);

        // Notify User
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\TransactionStatusMail($transaction, $user->name));
        } catch (\Exception $e) {
            report($e);
        }
        
        return redirect()->route('transactions')->with('success', 'Deposit completed successfully!');
    }
    
    /**
     * Handle deposit via payment methods (pending admin approval)
     */
    private function storePaymentDeposit(Request $request, $user)
    {
        $methodKey = $request->input('method');
        
        // Fetch payment method from database
        $paymentMethod = PaymentMethod::where('key', $methodKey)
            ->where('enabled', true)
            ->first();
        
        if (!$paymentMethod) {
            return redirect()->back()->with('error', 'Selected deposit method is not available');
        }
        
        // Check if payment reference is required for this method
        $requiresReference = $paymentMethod->requires_reference;
        
        // Get all available method keys for validation
        $availableMethods = PaymentMethod::enabled()->pluck('key')->toArray();
        
        $cryptoCurrencyRule = ['nullable', 'string'];
        
        if ($paymentMethod->type === 'crypto') {
            $configuredCurrencies = [];
            
            if (is_array($paymentMethod->configuration) && !empty($paymentMethod->configuration['currencies'])) {
                $configuredCurrencies = array_keys($paymentMethod->configuration['currencies']);
            }
            
            if (!empty($configuredCurrencies)) {
                $cryptoCurrencyRule[] = Rule::in($configuredCurrencies);
            }
        }
        
        $validated = $request->validate([
            'method' => 'required|string|in:' . implode(',', $availableMethods),
            'amount' => 'required|numeric|min:10',
            'payment_reference' => $requiresReference ? 'required|string|max:500' : 'nullable|string|max:500',
            'crypto_currency' => $cryptoCurrencyRule,
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $amountInCents = (int) ($validated['amount'] * 100);
        
        // Check minimum amount
        if ($amountInCents < $paymentMethod->min_amount) {
            return redirect()->back()->with('error', 'Minimum deposit amount is $' . number_format($paymentMethod->min_amount / 100, 2));
        }
        
        // Check maximum amount if set
        if ($paymentMethod->max_amount && $amountInCents > $paymentMethod->max_amount) {
            return redirect()->back()->with('error', 'Maximum deposit amount is $' . number_format($paymentMethod->max_amount / 100, 2));
        }
        
        // Calculate fees if applicable
        $fee = 0;
        if ($paymentMethod->fee_fixed) {
            $fee = $paymentMethod->fee_fixed;
        } elseif ($paymentMethod->fee_percentage) {
            $fee = (int) ($amountInCents * (floatval($paymentMethod->fee_percentage) / 100));
        }
        
        // Build metadata
        $metadata = [
            'deposit_type' => 'payment',
            'method' => $methodKey,
            'method_name' => $paymentMethod->name,
            'processing_time' => $paymentMethod->processing_time ?? 'N/A',
        ];
        
        // Add payment reference if provided
        if (!empty($validated['payment_reference'])) {
            $metadata['payment_reference'] = $validated['payment_reference'];
        }
        
        if ($methodKey === 'crypto' && !empty($validated['crypto_currency'])) {
            $metadata['crypto_currency'] = $validated['crypto_currency'];
        }
        
        if (!empty($validated['notes'])) {
            $metadata['user_notes'] = $validated['notes'];
        }
        
        // Create pending transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amountInCents,
            'fee' => $fee,
            'reference' => 'DEP-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'description' => 'Deposit via ' . $paymentMethod->name,
            'metadata' => $metadata,
        ]);

        // Notify Admin
        try {
            $adminEmail = \App\Support\SettingsManager::get('site_email', config('mail.from.address'));
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->queue(new \App\Mail\AdminAlertMail(
                    'New Deposit Request',
                    "User {$user->name} has submitted a deposit request of " . number_format($validated['amount'], 2) . " via {$paymentMethod->name}.",
                    'info',
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
        
        return redirect()->route('transactions')->with('success', 'Deposit request submitted! An admin will verify and process your payment shortly.');
    }
}

