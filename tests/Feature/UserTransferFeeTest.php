<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\TransactionCode;
use App\Models\User;
use App\Models\Wallet;
use App\Support\SettingsManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTransferFeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SettingsManager::flushCache();
    }

    public function test_wire_transfer_debits_default_percentage_fee(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
        $user = User::factory()->create([
            'status' => 'active',
            'transaction_pin' => '123456',
        ]);
        $wallet = $this->createWallet($user, 20000);
        $code = $this->createTransferCode($admin, 'WIRE-CODE-1');

        $response = $this->actingAs($user)->post(route('transfer.wire'), [
            'beneficiary_name' => 'Jane External',
            'bank_name' => 'External Bank',
            'account_number' => '1234567890',
            'routing_number' => '021000021',
            'swift_code' => 'BOFAUS3N',
            'beneficiary_address' => '1 Main Street',
            'amount' => '100.00',
            'description' => 'Wire transfer test',
            'transaction_pin' => '123456',
            'transaction_code' => $code->code,
        ]);

        $transaction = Transaction::where('reference', 'like', 'WIRE-%')->first();

        $this->assertNotNull($transaction);
        $response->assertRedirect(route('transfer.success', $transaction));
        $this->assertSame(10000, $transaction->amount);
        $this->assertSame(200, $transaction->fee);
        $this->assertSame('pending', $transaction->status);

        $wallet->refresh();
        $this->assertSame(9800, (int) $wallet->balance);
        $this->assertSame(9800, (int) $wallet->ledger_balance);

        $code->refresh();
        $this->assertTrue($code->is_used);
        $this->assertSame($transaction->id, $code->transaction_id);
    }

    public function test_wire_transfer_uses_configured_percentage_fee(): void
    {
        Mail::fake();
        SettingsManager::set(['wire_transfer_fee_percentage' => 5.0]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
        $user = User::factory()->create([
            'status' => 'active',
            'transaction_pin' => '123456',
        ]);
        $wallet = $this->createWallet($user, 20000);
        $code = $this->createTransferCode($admin, 'WIRE-CODE-2');

        $response = $this->actingAs($user)->post(route('transfer.wire'), [
            'beneficiary_name' => 'Jane External',
            'bank_name' => 'External Bank',
            'account_number' => '1234567890',
            'routing_number' => '021000021',
            'swift_code' => 'BOFAUS3N',
            'beneficiary_address' => '1 Main Street',
            'amount' => '100.00',
            'description' => 'Wire transfer test',
            'transaction_pin' => '123456',
            'transaction_code' => $code->code,
        ]);

        $transaction = Transaction::where('reference', 'like', 'WIRE-%')->first();

        $this->assertNotNull($transaction);
        $response->assertRedirect(route('transfer.success', $transaction));
        $this->assertSame(10000, $transaction->amount);
        $this->assertSame(500, $transaction->fee);
        $this->assertEquals(5.0, data_get($transaction->metadata, 'fee_percentage'));

        $wallet->refresh();
        $this->assertSame(9500, (int) $wallet->balance);
        $this->assertSame(9500, (int) $wallet->ledger_balance);
    }

    public function test_internal_transfer_has_no_transfer_fee(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
        $sender = User::factory()->create([
            'status' => 'active',
            'transaction_pin' => '123456',
        ]);
        $recipient = User::factory()->create([
            'status' => 'active',
        ]);
        $senderWallet = $this->createWallet($sender, 20000);
        $recipientWallet = $this->createWallet($recipient, 1000);
        $code = $this->createTransferCode($admin, 'INT-CODE-1');

        $response = $this->actingAs($sender)->post(route('transfer.internal'), [
            'recipient_account' => $recipientWallet->account_number,
            'amount' => '100.00',
            'description' => 'Internal transfer test',
            'transaction_pin' => '123456',
            'transaction_code' => $code->code,
        ]);

        $transaction = Transaction::where('reference', 'like', 'INT-%')->first();

        $this->assertNotNull($transaction);
        $response->assertRedirect(route('transfer.success', $transaction));
        $this->assertSame(10000, $transaction->amount);
        $this->assertSame(0, $transaction->fee);
        $this->assertSame('completed', $transaction->status);

        $senderWallet->refresh();
        $recipientWallet->refresh();
        $this->assertSame(10000, (int) $senderWallet->balance);
        $this->assertSame(11000, (int) $recipientWallet->balance);
    }

    private function createWallet(User $user, int $balance): Wallet
    {
        return Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => $balance,
            'ledger_balance' => $balance,
            'currency' => 'USD',
            'status' => 'active',
        ]);
    }

    private function createTransferCode(User $admin, string $code): TransactionCode
    {
        return TransactionCode::create([
            'code' => $code,
            'type' => 'transfer',
            'created_by' => $admin->id,
            'expires_at' => now()->addDay(),
            'is_used' => false,
        ]);
    }
}
