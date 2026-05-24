<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBalanceAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_credit_user_balance(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'balance' => 100000,
        ]);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 100000,
            'ledger_balance' => 100000,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->from("/admin/users/{$user->id}")
            ->post(route('admin.users.fund', $user), [
                'action' => 'credit',
                'amount' => 250.50,
                'description' => 'Manual top-up',
                'reference' => 'CRD-TEST-001',
                'notify_user' => true,
            ]);

        $response->assertRedirect("/admin/users/{$user->id}");
        $response->assertSessionHas('success', 'User balance funded successfully.');

        $user->refresh();
        $wallet->refresh();

        $this->assertSame(125050, $user->balance);
        $this->assertSame('125050.00', $wallet->balance);
        $this->assertSame('125050.00', $wallet->ledger_balance);

        $transaction = Transaction::where('reference', 'CRD-TEST-001')->first();
        $this->assertNotNull($transaction);
        $this->assertSame('deposit', $transaction->type);
        $this->assertSame(25050, $transaction->amount);
        $this->assertSame('credit', data_get($transaction->metadata, 'action'));

        $auditLog = AuditLog::where('event', 'user.balance_adjusted')->latest('id')->first();
        $this->assertNotNull($auditLog);
        $this->assertSame('credit', data_get($auditLog->details, 'action'));
    }

    public function test_admin_can_debit_user_balance(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'balance' => 100000,
        ]);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 100000,
            'ledger_balance' => 100000,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->from("/admin/users/{$user->id}")
            ->post(route('admin.users.fund', $user), [
                'action' => 'debit',
                'amount' => 125.25,
                'description' => 'Manual deduction',
                'reference' => 'DBT-TEST-001',
                'notify_user' => false,
            ]);

        $response->assertRedirect("/admin/users/{$user->id}");
        $response->assertSessionHas('success', 'User balance deducted successfully.');

        $user->refresh();
        $wallet->refresh();

        $this->assertSame(87475, $user->balance);
        $this->assertSame('87475.00', $wallet->balance);
        $this->assertSame('87475.00', $wallet->ledger_balance);

        $transaction = Transaction::where('reference', 'DBT-TEST-001')->first();
        $this->assertNotNull($transaction);
        $this->assertSame('withdrawal', $transaction->type);
        $this->assertSame(12525, $transaction->amount);
        $this->assertSame('debit', data_get($transaction->metadata, 'action'));
    }

    public function test_admin_cannot_debit_more_than_available_balance(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'balance' => 5000,
        ]);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 5000,
            'ledger_balance' => 5000,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)
            ->from("/admin/users/{$user->id}")
            ->post(route('admin.users.fund', $user), [
                'action' => 'debit',
                'amount' => 100.00,
                'description' => 'Too much',
            ]);

        $response->assertRedirect("/admin/users/{$user->id}");
        $response->assertSessionHasErrors(['amount']);

        $user->refresh();
        $wallet->refresh();

        $this->assertSame(5000, $user->balance);
        $this->assertSame('5000.00', $wallet->balance);
        $this->assertSame('5000.00', $wallet->ledger_balance);
        $this->assertDatabaseCount('transactions', 0);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
    }
}
