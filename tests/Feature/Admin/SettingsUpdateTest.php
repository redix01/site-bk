<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use App\Support\SettingsManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        SettingsManager::flushCache();
    }

    /** @test */
    public function admin_can_update_general_and_security_settings(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $payload = [
            'site_name' => 'Banko Pro',
            'site_email' => 'hello@banko.test',
            'support_email' => 'support@banko.test',
            'app_url' => 'https://banko.test',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'security_max_login_attempts' => 4,
            'security_lockout_time' => 45,
            'security_session_timeout' => 60,
            'security_two_factor_threshold' => 150000,
            'security_admin_approval_threshold' => 250000,
        ];

        $response = $this->actingAs($admin)
            ->withoutMiddleware([
                \App\Http\Middleware\AdminMiddleware::class,
                \App\Http\Middleware\Admin\AdminMiddleware::class,
            ])
            ->put('/admin/settings', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Settings updated successfully.');

        $this->assertDatabaseHas('settings', [
            'key' => 'site_name',
            'value' => 'Banko Pro',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'security_max_login_attempts',
            'value' => '4',
        ]);

        SettingsManager::flushCache();

        $this->assertSame('Banko Pro', SettingsManager::get('site_name'));
        $this->assertSame(4, SettingsManager::get('security_max_login_attempts'));
        $this->assertSame(150000.0, SettingsManager::get('security_two_factor_threshold'));
    }

    /** @test */
    public function admin_can_upload_and_replace_logo(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $existingPath = 'branding/existing-logo.png';
        Storage::disk('public')->put($existingPath, 'fake');

        Setting::create([
            'key' => 'site_logo_path',
            'value' => $existingPath,
            'type' => 'string',
        ]);

        SettingsManager::flushCache();

        $payload = [
            'site_name' => 'Banko Pro',
            'site_email' => 'hello@banko.test',
            'support_email' => 'support@banko.test',
            'app_url' => 'https://banko.test',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'security_max_login_attempts' => 4,
            'security_lockout_time' => 45,
            'security_session_timeout' => 60,
            'security_two_factor_threshold' => 150000,
            'security_admin_approval_threshold' => 250000,
            'site_logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ];

        $response = $this->actingAs($admin)
            ->withoutMiddleware([
                \App\Http\Middleware\AdminMiddleware::class,
                \App\Http\Middleware\Admin\AdminMiddleware::class,
            ])
            ->put('/admin/settings', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Settings updated successfully.');

        $this->assertDatabaseHas('settings', [
            'key' => 'site_logo_path',
        ]);

        SettingsManager::flushCache();

        $newLogoPath = SettingsManager::get('site_logo_path');

        $this->assertNotNull($newLogoPath);
        $this->assertNotEquals($existingPath, $newLogoPath);
        Storage::disk('public')->assertExists($newLogoPath);
        Storage::disk('public')->assertMissing($existingPath);
    }
}


