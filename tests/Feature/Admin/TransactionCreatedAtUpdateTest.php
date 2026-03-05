<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionCreatedAtUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_transaction_created_date_and_preserve_time(): void
    {
        $admin = $this->createAdmin();
        $transactionOwner = User::factory()->create();

        $originalCreatedAt = Carbon::parse('2024-10-03 14:35:27');
        $transaction = $this->createTransactionForUser($transactionOwner, $originalCreatedAt);

        $newDate = '2025-01-20';
        $showUrl = "/admin/transactions/{$transaction->id}";

        $response = $this->actingAs($admin)
            ->from($showUrl)
            ->patch(route('admin.transactions.created-at', $transaction), [
                'created_at' => $newDate,
            ]);

        $response->assertRedirect($showUrl);
        $response->assertSessionHas('success', 'Transaction date updated successfully.');

        $transaction->refresh();

        $this->assertSame($newDate, $transaction->created_at->toDateString());
        $this->assertSame($originalCreatedAt->format('H:i:s'), $transaction->created_at->format('H:i:s'));

        $auditLog = AuditLog::where('event', 'transaction.created_at_updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($auditLog);
        $this->assertSame($admin->id, $auditLog->actor_id);
        $this->assertSame(Transaction::class, $auditLog->auditable_type);
        $this->assertSame($transaction->id, $auditLog->auditable_id);
        $this->assertSame($originalCreatedAt->toIso8601String(), data_get($auditLog->details, 'old_created_at'));
        $this->assertSame($transaction->created_at->toIso8601String(), data_get($auditLog->details, 'new_created_at'));
    }

    public function test_update_transaction_created_date_requires_valid_date(): void
    {
        $admin = $this->createAdmin();
        $transactionOwner = User::factory()->create();

        $originalCreatedAt = Carbon::parse('2024-06-12 09:17:44');
        $transaction = $this->createTransactionForUser($transactionOwner, $originalCreatedAt);
        $showUrl = "/admin/transactions/{$transaction->id}";

        $response = $this->actingAs($admin)
            ->from($showUrl)
            ->patch(route('admin.transactions.created-at', $transaction), [
                'created_at' => 'not-a-date',
            ]);

        $response->assertRedirect($showUrl);
        $response->assertSessionHasErrors(['created_at']);

        $transaction->refresh();

        $this->assertSame($originalCreatedAt->toDateTimeString(), $transaction->created_at->toDateTimeString());
    }

    public function test_non_admin_cannot_update_transaction_created_date(): void
    {
        $regularUser = User::factory()->create([
            'is_admin' => false,
            'status' => 'active',
        ]);

        $transactionOwner = User::factory()->create();
        $transaction = $this->createTransactionForUser($transactionOwner, Carbon::parse('2024-03-01 11:20:33'));

        $response = $this->actingAs($regularUser)
            ->patch(route('admin.transactions.created-at', $transaction), [
                'created_at' => '2024-07-15',
            ]);

        $response->assertForbidden();
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
    }

    private function createTransactionForUser(User $user, Carbon $createdAt): Transaction
    {
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 125000,
            'fee' => 0,
            'reference' => 'TST-' . Str::upper(Str::random(12)),
            'status' => 'completed',
            'description' => 'Transaction created for testing.',
        ]);

        $transaction->forceFill([
            'created_at' => $createdAt->copy(),
            'updated_at' => $createdAt->copy(),
        ])->save();

        return $transaction->fresh();
    }
}
