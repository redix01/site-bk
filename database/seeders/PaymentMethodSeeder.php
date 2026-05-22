<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'type' => 'bank',
                'name' => 'Bank Transfer',
                'key' => 'bank_transfer',
                'enabled' => true,
                'min_amount' => 1000, // $10
                'max_amount' => null,
                'processing_time' => '1-3 business days',
                'fee_percentage' => null,
                'fee_fixed' => null,
                'requires_reference' => false,
                'sort_order' => 1,
                'configuration' => [
                    'bank_name' => env('BANK_NAME', 'Obsidian Wealth'),
                    'routing_number' => env('BANK_ROUTING_NUMBER', '021000021'),
                ],
                'instructions' => [
                    'Bank Name' => env('BANK_NAME', 'Obsidian Wealth'),
                    'Account Name' => '{{USER_NAME}}',
                    'Account Number' => '{{ACCOUNT_NUMBER}}',
                    'Routing Number' => env('BANK_ROUTING_NUMBER', '021000021'),
                ],
                'notes' => [
                    'This is YOUR account details for receiving deposits',
                    'Share these details with anyone sending you money',
                    'Deposits will reflect in your account within 1-3 business days',
                    'Contact support if you have any questions',
                ],
            ],
            [
                'type' => 'crypto',
                'name' => 'Cryptocurrency',
                'key' => 'crypto',
                'enabled' => true,
                'min_amount' => 1000, // $10
                'max_amount' => null,
                'processing_time' => '30-60 minutes after 3 confirmations',
                'fee_percentage' => null,
                'fee_fixed' => null,
                'requires_reference' => true,
                'sort_order' => 2,
                'configuration' => [
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
                ],
                'instructions' => [],
                'notes' => [
                    'Send the exact amount to the address shown',
                    'Include your transaction hash in the deposit form',
                    'Wait for network confirmations (varies by currency)',
                    'Ensure you select the correct network',
                ],
            ],
            [
                'type' => 'paypal',
                'name' => 'PayPal',
                'key' => 'paypal',
                'enabled' => true,
                'min_amount' => 1000, // $10
                'max_amount' => null,
                'processing_time' => '1-2 business days',
                'fee_percentage' => 2.90,
                'fee_fixed' => null,
                'requires_reference' => true,
                'sort_order' => 3,
                'configuration' => [
                    'paypal_email' => env('PAYPAL_EMAIL', 'payments@obsidianwealths.com'),
                ],
                'instructions' => [
                    'PayPal Email' => env('PAYPAL_EMAIL', 'payments@obsidianwealths.com'),
                    'Payment Type' => 'Friends & Family (to avoid fees) or Goods & Services',
                ],
                'notes' => [
                    'Include your account number in the payment note',
                    'A 2.9% processing fee may apply for Goods & Services payments',
                    'Send payment to the email shown above',
                    'Take a screenshot of the completed payment',
                ],
            ],
            [
                'type' => 'wire_transfer',
                'name' => 'Wire Transfer (International)',
                'key' => 'wire_transfer',
                'enabled' => true,
                'min_amount' => 10000, // $100
                'max_amount' => null,
                'processing_time' => '3-5 business days',
                'fee_percentage' => null,
                'fee_fixed' => 2500, // $25
                'requires_reference' => true,
                'sort_order' => 4,
                'configuration' => [
                    'bank_name' => env('BANK_NAME', 'Obsidian Wealth'),
                    'swift_code' => env('BANK_SWIFT_CODE', 'BANKOUS33'),
                    'routing_number' => env('BANK_ROUTING_NUMBER', '021000021'),
                    'bank_address' => env('BANK_ADDRESS', '123 Banking Street, New York, NY 10001, USA'),
                ],
                'instructions' => [
                    'Beneficiary Bank' => env('BANK_NAME', 'Obsidian Wealth'),
                    'Beneficiary Name' => env('BANK_ACCOUNT_NAME', 'Obsidian Wealth Ltd'),
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
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['key' => $method['key']],
                $method
            );
        }
    }
}
