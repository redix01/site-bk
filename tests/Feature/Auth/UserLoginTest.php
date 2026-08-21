<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_can_login_with_email_and_password(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'status' => 'active',
        ]);

        $response = $this->post('/login', array_merge($this->securityPayload(), [
            'email' => $user->email,
            'password' => 'password',
        ]));

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_is_redirected_to_admin_login_when_using_user_login_form(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'status' => 'active',
        ]);

        $response = $this->post('/login', array_merge($this->securityPayload(), [
            'email' => $admin->email,
            'password' => 'password',
        ]));

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'status' => 'active',
        ]);

        $response = $this->from('/login')->post('/login', array_merge($this->securityPayload(), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]));

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    private function securityPayload(): array
    {
        return [
            'internal_code' => '',
            'page_loaded_at' => (string) now()->subSeconds(5)->timestamp,
            'interaction_token' => 'human',
        ];
    }
}
