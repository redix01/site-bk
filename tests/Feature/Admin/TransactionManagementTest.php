<?php

namespace Tests\Feature\Admin;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_transaction_with_new_type_and_optional_description(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.transactions.store'), [
                'user_id' => $user->id,
                'type' => 'stamp_duty',
                'amount' => '12.50',
                'status' => 'completed',
            ]);

        $response->assertRedirect(route('admin.transactions.index'));
        $response->assertSessionHas('success', 'Transaction created successfully.');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'stamp_duty',
            'amount' => 1250,
            'status' => 'completed',
            'description' => null,
        ]);
    }

    public function test_admin_can_update_transaction_with_new_type_and_optional_description(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 2500,
            'fee' => 0,
            'reference' => 'TST-' . Str::upper(Str::random(12)),
            'status' => 'pending',
            'description' => 'Initial description',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.transactions.update', $transaction), [
                'user_id' => $user->id,
                'type' => 'monthly_fee',
                'amount' => '9.99',
                'status' => 'completed',
                'description' => '',
            ]);

        $response->assertRedirect(route('admin.transactions.index'));
        $response->assertSessionHas('success', 'Transaction updated successfully.');

        $transaction->refresh();

        $this->assertSame('monthly_fee', $transaction->type);
        $this->assertSame(999, $transaction->amount);
        $this->assertNull($transaction->description);
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);
    }
}
