<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Transaction Settings
    |--------------------------------------------------------------------------
    |
    | This section contains settings related to transactions.
    |
    */
    'transactions' => [
        // Maximum amount that can be transferred in a single transaction (in kobo)
        'max_amount' => env('MAX_TRANSACTION_AMOUNT', 100000000), // 1,000,000 NGN
        
        // Minimum amount for a transaction (in kobo)
        'min_amount' => env('MIN_TRANSACTION_AMOUNT', 100), // 1 NGN
        
        // Default transaction fee (in kobo)
        'default_fee' => env('DEFAULT_TRANSACTION_FEE', 5000), // 50 NGN
        
        // Fee structure for different transaction types
        'fees' => [
            'transfer' => [
                'within_bank' => [
                    'fixed' => 1000, // 10 NGN
                    'percentage' => 0.5, // 0.5%
                ],
                'other_banks' => [
                    'fixed' => 5000, // 50 NGN
                    'percentage' => 0.75, // 0.75%
                ],
            ],
            'withdrawal' => [
                'atm' => [
                    'fixed' => 1000, // 10 NGN
                    'percentage' => 0.5, // 0.5%
                ],
                'bank' => [
                    'fixed' => 500, // 5 NGN
                    'percentage' => 0.25, // 0.25%
                ],
            ],
            'deposit' => [
                'fixed' => 0,
                'percentage' => 0,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Account Settings
    |--------------------------------------------------------------------------
    |
    | This section contains settings related to user accounts.
    |
    */
    'accounts' => [
        // Default account type for new users
        'default_type' => 'savings',
        
        // Minimum balance required for different account types (in kobo)
        'minimum_balance' => [
            'savings' => 1000, // 10 NGN
            'current' => 5000, // 50 NGN
            'corporate' => 100000, // 1,000 NGN
        ],
        
        // Daily transfer limits (in kobo)
        'daily_limits' => [
            'savings' => 5000000, // 50,000 NGN
            'current' => 10000000, // 100,000 NGN
            'corporate' => 100000000, // 1,000,000 NGN
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | This section contains security-related settings.
    |
    */
    'security' => [
        // Number of failed login attempts before account is locked
        'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
        
        // Lockout time in minutes after max login attempts
        'lockout_time' => env('ACCOUNT_LOCKOUT_TIME', 30),
        
        // Session timeout in minutes
        'session_timeout' => env('SESSION_TIMEOUT', 30),
        
        // Require 2FA for transactions above this amount (in kobo)
        'two_factor_threshold' => env('TWO_FACTOR_THRESHOLD', 100000), // 1,000 NGN
        
        // Require admin approval for transactions above this amount (in kobo)
        'admin_approval_threshold' => env('ADMIN_APPROVAL_THRESHOLD', 1000000), // 10,000 NGN
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | This section contains settings related to notifications.
    |
    */
    'notifications' => [
        // Send email notifications
        'email' => env('NOTIFY_EMAIL', true),
        
        // Send SMS notifications
        'sms' => env('NOTIFY_SMS', true),
        
        // Send push notifications
        'push' => env('NOTIFY_PUSH', true),
        
        // Send in-app notifications
        'in_app' => env('NOTIFY_IN_APP', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reference Number Settings
    |--------------------------------------------------------------------------
    |
    | This section contains settings for generating reference numbers.
    |
    */
    'references' => [
        // Prefix for transaction references
        'transaction_prefix' => 'TXN',
        
        // Prefix for account numbers
        'account_prefix' => '10',
        
        // Length of the random part of reference numbers
        'random_length' => 8,
    ],

    /*
    |--------------------------------------------------------------------------
    | Bank Code
    |--------------------------------------------------------------------------
    |
    | The bank identification code used as the first 3 digits of all account numbers.
    | This identifies your bank uniquely in the banking system.
    |
    */
    'bank_code' => env('BANK_CODE', '100'),

    /*
    |--------------------------------------------------------------------------
    | Deposit Methods
    |--------------------------------------------------------------------------
    |
    | Available deposit methods and their payment details.
    | Users can select these methods to deposit funds into their wallet.
    |
    */
    'deposit_methods' => [
        'bank_transfer' => [
            'name' => 'Bank Transfer',
            'enabled' => true,
            'requires_form' => false, // View-only, no form needed
            'requires_reference' => false,
            'processing_time' => '1-3 business days',
            'instructions' => [
                'Bank Name' => env('BANK_NAME', 'Bank'),
                'Account Name' => '{{USER_NAME}}', // Will be replaced with user's name
                'Account Number' => '{{ACCOUNT_NUMBER}}', // Will be replaced with user's account number
                'Routing Number' => env('BANK_ROUTING_NUMBER', '021000021'),
            ],
            'notes' => [
                'This is YOUR account details for receiving deposits',
                'Share these details with anyone sending you money',
                'Deposits will reflect in your account within 1-3 business days',
                'Contact support if you have any questions',
            ],
        ],
        'crypto' => [
            'name' => 'Cryptocurrency',
            'enabled' => true,
            'min_amount' => 1000, // $10.00
            'processing_time' => '30-60 minutes after 3 confirmations',
            'currencies' => [
                'BTC' => [
                    'name' => 'Bitcoin (BTC)',
                    'address' => env('CRYPTO_BTC_ADDRESS', 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh'),
                    'network' => 'Bitcoin',
                ],
                'ETH' => [
                    'name' => 'Ethereum (ETH)',
                    'address' => env('CRYPTO_ETH_ADDRESS', '0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb'),
                    'network' => 'Ethereum (ERC-20)',
                ],
                'USDT' => [
                    'name' => 'Tether (USDT)',
                    'address' => env('CRYPTO_USDT_ADDRESS', '0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb'),
                    'network' => 'Ethereum (ERC-20) / Tron (TRC-20)',
                ],
            ],
            'notes' => [
                'Send the exact amount to the address shown',
                'Include your transaction hash in the deposit form',
                'Wait for network confirmations (varies by currency)',
                'Ensure you select the correct network',
            ],
        ],
        'paypal' => [
            'name' => 'PayPal',
            'enabled' => true,
            'min_amount' => 1000, // $10.00
            'processing_time' => '1-2 business days',
            'fee_percentage' => 2.9, // 2.9%
            'instructions' => [
                'PayPal Email' => env('PAYPAL_EMAIL', 'payments@example.com'),
                'Payment Type' => 'Friends & Family (to avoid fees) or Goods & Services',
            ],
            'notes' => [
                'Include your account number in the payment note',
                'A 2.9% processing fee may apply for Goods & Services payments',
                'Send payment to the email shown above',
                'Take a screenshot of the completed payment',
            ],
        ],
        'wire_transfer' => [
            'name' => 'Wire Transfer (International)',
            'enabled' => true,
            'min_amount' => 10000, // $100.00
            'processing_time' => '3-5 business days',
            'fee' => 2500, // $25.00 flat fee
            'instructions' => [
                'Beneficiary Bank' => env('BANK_NAME', 'Bank'),
                'Beneficiary Name' => env('BANK_ACCOUNT_NAME', 'Bank Ltd'),
                'Beneficiary Account' => env('BANK_ACCOUNT_NUMBER', '1234567890'),
                'Bank Address' => env('BANK_ADDRESS', '123 Banking Street, New York, NY 10001, USA'),
                'SWIFT/BIC Code' => env('BANK_SWIFT_CODE', 'BANKOUS33'),
                'Routing Number' => env('BANK_ROUTING_NUMBER', '021000021'),
            ],
            'notes' => [
                'Wire transfers incur a $25 processing fee',
                'Minimum deposit amount: $100',
                'Include your account number in the wire reference',
                'International wires may take 3-5 business days',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | Define the list of ISO 4217 currency codes that admins can assign to users.
    |
    */
    'supported_currencies' => [
        'USD',
        'EUR',
        'GBP',
        'JPY',
        'CAD',
        'AUD',
        'NGN',
        'CHF',
        'CNY',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | This section contains settings for the API.
    |
    */
    'api' => [
        // Default number of items per page for pagination
        'per_page' => 15,
        
        // Maximum number of items per page
        'max_per_page' => 100,
        
        // Rate limiting (requests per minute)
        'rate_limit' => env('API_RATE_LIMIT', 60),
    ],
];
