<?php

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency.
     *
     * @param  float  $amount
     * @param  string  $currency
     * @param  int  $decimals
     * @return string
     */
    function format_currency($amount, $currency = 'AUD', $decimals = 2)
    {
        $formatted = number_format($amount, $decimals);
        
        switch (strtoupper($currency)) {
            case 'USD':
                return '$' . $formatted;
            case 'EUR':
                return '€' . $formatted;
            case 'GBP':
                return '£' . $formatted;
            case 'AUD':
                return 'A$' . $formatted;
            case 'NGN':
                return '₦' . $formatted;
            default:
                return 'A$' . $formatted;
        }
    }
}

if (!function_exists('generate_account_number')) {
    /**
     * Generate a unique account number.
     *
     * @return string
     */
    function generate_account_number()
    {
        $prefix = config('banking.references.account_prefix', '10');
        $length = config('banking.references.random_length', 8);
        
        do {
            $random = str_pad(mt_rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
            $accountNumber = $prefix . $random;
        } while (User::where('account_number', $accountNumber)->exists());
        
        return $accountNumber;
    }
}

if (!function_exists('generate_transaction_reference')) {
    /**
     * Generate a unique transaction reference.
     *
     * @return string
     */
    function generate_transaction_reference()
    {
        $prefix = config('banking.references.transaction_prefix', 'TXN');
        $length = config('banking.references.random_length', 8);
        
        do {
            $random = strtoupper(Str::random($length));
            $reference = $prefix . $random;
        } while (Transaction::where('reference', $reference)->exists());
        
        return $reference;
    }
}

if (!function_exists('calculate_transaction_fee')) {
    /**
     * Calculate the fee for a transaction.
     *
     * @param  float  $amount
     * @param  string  $type
     * @param  string  $channel
     * @return float
     */
    function calculate_transaction_fee($amount, $type = 'transfer', $channel = 'within_bank')
    {
        $fees = config("banking.transactions.fees.{$type}.{$channel}", [
            'fixed' => 0,
            'percentage' => 0,
        ]);
        
        $fixedFee = $fees['fixed'] ?? 0;
        $percentageFee = $fees['percentage'] ?? 0;
        
        return $fixedFee + ($amount * $percentageFee / 100);
    }
}

if (!function_exists('mask_account_number')) {
    /**
     * Mask part of an account number for display.
     *
     * @param  string  $accountNumber
     * @param  int  $visible  Number of characters to show at the end
     * @return string
     */
    function mask_account_number($accountNumber, $visible = 4)
    {
        $length = strlen($accountNumber);
        if ($length <= $visible) {
            return $accountNumber;
        }
        
        $masked = str_repeat('*', $length - $visible);
        $masked .= substr($accountNumber, -$visible);
        
        return $masked;
    }
}

if (!function_exists('format_transaction_type')) {
    /**
     * Format a transaction type for display.
     *
     * @param  string  $type
     * @return string
     */
    function format_transaction_type($type)
    {
        return ucfirst(str_replace('_', ' ', $type));
    }
}

if (!function_exists('get_transaction_status_badge')) {
    /**
     * Get a badge HTML for a transaction status.
     *
     * @param  string  $status
     * @return string
     */
    function get_transaction_status_badge($status)
    {
        $statuses = [
            'pending' => [
                'class' => 'bg-yellow-100 text-yellow-800',
                'text' => 'Pending',
            ],
            'completed' => [
                'class' => 'bg-green-100 text-green-800',
                'text' => 'Completed',
            ],
            'failed' => [
                'class' => 'bg-red-100 text-red-800',
                'text' => 'Failed',
            ],
            'reversed' => [
                'class' => 'bg-gray-100 text-gray-800',
                'text' => 'Reversed',
            ],
            'declined' => [
                'class' => 'bg-red-100 text-red-800',
                'text' => 'Declined',
            ],
            'processing' => [
                'class' => 'bg-blue-100 text-blue-800',
                'text' => 'Processing',
            ],
            'refunded' => [
                'class' => 'bg-purple-100 text-purple-800',
                'text' => 'Refunded',
            ],
        ];
        
        $status = strtolower($status);
        $statusData = $statuses[$status] ?? [
            'class' => 'bg-gray-100 text-gray-800',
            'text' => ucfirst($status),
        ];
        
        return sprintf(
            '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full %s">%s</span>',
            $statusData['class'],
            $statusData['text']
        );
    }
}

if (!function_exists('get_transaction_icon')) {
    /**
     * Get an icon for a transaction type.
     *
     * @param  string  $type
     * @return string
     */
    function get_transaction_icon($type)
    {
        $icons = [
            'transfer' => 'arrow-right',
            'deposit' => 'arrow-down',
            'withdrawal' => 'arrow-up',
            'payment' => 'credit-card',
            'refund' => 'refresh-cw',
            'fee' => 'dollar-sign',
            'interest' => 'percent',
            'bonus' => 'gift',
        ];
        
        return $icons[strtolower($type)] ?? 'dollar-sign';
    }
}
