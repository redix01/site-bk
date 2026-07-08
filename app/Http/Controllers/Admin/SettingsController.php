<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'site' => [
                'name' => SettingsManager::get('site_name', config('app.name')),
                'email' => SettingsManager::get('site_email', config('mail.from.address')),
                'support_email' => SettingsManager::get('support_email', env('MAIL_SUPPORT')),
                'url' => SettingsManager::get('app_url', config('app.url')),
                'timezone' => SettingsManager::get('timezone', config('app.timezone')),
                'currency' => SettingsManager::get('currency', 'AUD'),
            ],
            'branding' => [
                'logo_path' => SettingsManager::get('site_logo_path'),
                'logo_url' => $this->logoUrl(),
            ],
            'security' => [
                'max_login_attempts' => SettingsManager::get('security_max_login_attempts', config('banking.security.max_login_attempts', 5)),
                'lockout_time' => SettingsManager::get('security_lockout_time', config('banking.security.lockout_time', 30)),
                'session_timeout' => SettingsManager::get('security_session_timeout', config('banking.security.session_timeout', 30)),
                'two_factor_threshold' => SettingsManager::get('security_two_factor_threshold', config('banking.security.two_factor_threshold', 100000)),
                'admin_approval_threshold' => SettingsManager::get('security_admin_approval_threshold', config('banking.security.admin_approval_threshold', 1000000)),
            ],
        ];

        return inertia('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        $nullableKeys = [
            'site_name',
            'site_email',
            'support_email',
            'app_url',
            'timezone',
            'currency',
            'security_max_login_attempts',
            'security_lockout_time',
            'security_session_timeout',
            'security_two_factor_threshold',
            'security_admin_approval_threshold',
        ];

        $sanitised = [];

        foreach ($nullableKeys as $key) {
            if (! $request->has($key)) {
                continue;
            }

            $value = $request->input($key);

            if (is_string($value)) {
                $value = trim($value);
            }

            $sanitised[$key] = $value === '' ? null : $value;
        }

        if (! empty($sanitised)) {
            $request->merge($sanitised);
        }

        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_email' => ['nullable', 'email', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'app_url' => ['nullable', 'url', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:10'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'security_max_login_attempts' => ['nullable', 'integer', 'min:1', 'max:20'],
            'security_lockout_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'security_session_timeout' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'security_two_factor_threshold' => ['nullable', 'numeric', 'min:0'],
            'security_admin_approval_threshold' => ['nullable', 'numeric', 'min:0'],
        ]);

        $logoPath = SettingsManager::get('site_logo_path');

        DB::beginTransaction();

        try {
            $payload = [
                'site_name' => $validated['site_name'] ?? null,
                'site_email' => $validated['site_email'] ?? null,
                'support_email' => $validated['support_email'] ?? null,
                'app_url' => $validated['app_url'] ?? null,
                'timezone' => $validated['timezone'] ?? null,
                'currency' => $validated['currency'] ?? null,
                'security_max_login_attempts' => array_key_exists('security_max_login_attempts', $validated)
                    ? (int) $validated['security_max_login_attempts']
                    : null,
                'security_lockout_time' => array_key_exists('security_lockout_time', $validated)
                    ? (int) $validated['security_lockout_time']
                    : null,
                'security_session_timeout' => array_key_exists('security_session_timeout', $validated)
                    ? (int) $validated['security_session_timeout']
                    : null,
                'security_two_factor_threshold' => array_key_exists('security_two_factor_threshold', $validated)
                    ? (float) $validated['security_two_factor_threshold']
                    : null,
                'security_admin_approval_threshold' => array_key_exists('security_admin_approval_threshold', $validated)
                    ? (float) $validated['security_admin_approval_threshold']
                    : null,
            ];

            if ($request->boolean('remove_logo')) {
                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }

                $payload['site_logo_path'] = null;
                $logoPath = null;
            } elseif ($request->hasFile('site_logo')) {
                $uploaded = $request->file('site_logo')->store('branding', 'public');

                if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                    Storage::disk('public')->delete($logoPath);
                }

                $payload['site_logo_path'] = $uploaded;
                $logoPath = $uploaded;
            }

            SettingsManager::set($payload);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            report($exception);

            return back()->with('error', 'Failed to update settings. Please try again.');
        }

        config([
            'app.name' => $payload['site_name'] ?? config('app.name'),
            'app.url' => $payload['app_url'] ?? config('app.url'),
            'app.timezone' => $payload['timezone'] ?? config('app.timezone'),
            'mail.from.address' => $payload['site_email'] ?? config('mail.from.address'),
        ]);

        AuditLog::logEvent('system.settings_updated', [
            'admin' => auth()->user()->email ?? 'system',
            'changed' => array_keys($payload),
        ]);

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Display the security settings page.
     */
    public function security()
    {
        return inertia('Admin/Settings/Security');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        AuditLog::logEvent('admin.password.updated', [], $user);

        Auth::logoutOtherDevices($validated['password']);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            AuditLog::logEvent('system.cache_cleared', [
                'admin' => auth()->user()->name,
            ]);

            return back()->with('success', 'Application cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Run database migrations.
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            AuditLog::logEvent('system.migrations_run', [
                'admin' => auth()->user()->name,
            ]);

            return back()->with('success', 'Database migrations executed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to run migrations: ' . $e->getMessage());
        }
    }

    /**
     * Download database backup.
     */
    public function backupDatabase()
    {
        try {
            $dbPath = database_path('database.sqlite');
            
            if (!file_exists($dbPath)) {
                return back()->with('error', 'Database file not found.');
            }

            $filename = 'backup_' . now()->format('Y-m-d_His') . '.sqlite';

            AuditLog::logEvent('system.database_backup', [
                'admin' => auth()->user()->name,
                'filename' => $filename,
            ]);

            return response()->download($dbPath, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to backup database: ' . $e->getMessage());
        }
    }

    /**
     * Get system information.
     */
    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_connection' => config('queue.default'),
            'disk_space' => $this->getDiskSpace(),
        ];

        return inertia('Admin/Settings/SystemInfo', [
            'info' => $info
        ]);
    }

    /**
     * Get available disk space.
     */
    private function getDiskSpace()
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');
        $used = $total - $free;

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => round(($used / $total) * 100, 2),
        ];
    }

    /**
     * Format bytes to human-readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Resolve the publicly accessible logo URL.
     */
    private function logoUrl(): ?string
    {
        $logoPath = SettingsManager::get('site_logo_path');

        if (! $logoPath) {
            return null;
        }

        if (! Storage::disk('public')->exists($logoPath)) {
            return null;
        }

        return Storage::disk('public')->url($logoPath);
    }
}
