<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserCreatedAtUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user_created_date_and_preserve_time(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'created_at' => Carbon::parse('2024-08-11 15:42:18'),
            'updated_at' => Carbon::parse('2024-08-11 15:42:18'),
        ]);

        $originalCreatedAt = $user->created_at->copy();
        $newDate = '2025-02-14';
        $showUrl = "/admin/users/{$user->id}";

        $response = $this->actingAs($admin)
            ->from($showUrl)
            ->patch(route('admin.users.created-at', $user), [
                'created_at' => $newDate,
            ]);

        $response->assertRedirect($showUrl);
        $response->assertSessionHas('success', 'Account created date updated successfully.');

        $user->refresh();

        $this->assertSame($newDate, $user->created_at->toDateString());
        $this->assertSame($originalCreatedAt->format('H:i:s'), $user->created_at->format('H:i:s'));

        $auditLog = AuditLog::where('event', 'user.created_at_updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($auditLog);
        $this->assertSame($admin->id, $auditLog->actor_id);
        $this->assertSame(User::class, $auditLog->auditable_type);
        $this->assertSame($user->id, $auditLog->auditable_id);
        $this->assertSame($originalCreatedAt->toIso8601String(), data_get($auditLog->details, 'old_created_at'));
        $this->assertSame($user->created_at->toIso8601String(), data_get($auditLog->details, 'new_created_at'));
    }

    public function test_update_user_created_date_requires_valid_date(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'created_at' => Carbon::parse('2024-05-03 09:12:41'),
            'updated_at' => Carbon::parse('2024-05-03 09:12:41'),
        ]);

        $originalCreatedAt = $user->created_at->copy();
        $showUrl = "/admin/users/{$user->id}";

        $response = $this->actingAs($admin)
            ->from($showUrl)
            ->patch(route('admin.users.created-at', $user), [
                'created_at' => 'invalid-date',
            ]);

        $response->assertRedirect($showUrl);
        $response->assertSessionHasErrors(['created_at']);

        $user->refresh();

        $this->assertSame($originalCreatedAt->toDateTimeString(), $user->created_at->toDateTimeString());
    }

    public function test_non_admin_cannot_update_user_created_date(): void
    {
        $regularUser = User::factory()->create([
            'is_admin' => false,
            'status' => 'active',
        ]);

        $targetUser = User::factory()->create();

        $response = $this->actingAs($regularUser)
            ->patch(route('admin.users.created-at', $targetUser), [
                'created_at' => '2025-01-01',
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
}
