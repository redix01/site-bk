<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\AuditLog;
use App\Models\LoginHistory;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $loginHistory = $user->loginHistory()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (LoginHistory $entry) {
                return [
                    'id' => $entry->id,
                    'ip_address' => $entry->ip_address,
                    'device' => $entry->device,
                    'platform' => $entry->platform,
                    'browser' => $entry->browser,
                    'location' => $entry->location,
                    'device_icon' => $entry->device_icon,
                    'login_successful' => $entry->login_successful,
                    'formatted_created_at' => $entry->formatted_created_at,
                ];
            });

        return inertia('Profile', [
            'loginHistory' => $loginHistory,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $profilePhoto = $request->file('profile_photo');
        unset($validated['profile_photo']);

        $user->update($validated);

        if ($profilePhoto) {
            Log::info('Profile photo upload received', [
                'user_id' => $user->id,
                'original_name' => $profilePhoto->getClientOriginalName(),
                'mime' => $profilePhoto->getMimeType(),
                'size_bytes' => $profilePhoto->getSize(),
                'disk' => 'public',
            ]);
        }

        $updatedFields = array_keys($validated);

        if ($profilePhoto) {
            $path = $profilePhoto->store('profile-photos', 'public');

            $publicUrl = null;
            $fileExists = false;
            try {
                $publicUrl = Storage::disk('public')->url($path);
                $fileExists = Storage::disk('public')->exists($path);
            } catch (\Throwable $e) {
                Log::error('Error generating public URL or checking existence for profile photo', [
                    'user_id' => $user->id,
                    'path' => $path,
                    'disk' => 'public',
                    'error' => $e->getMessage(),
                ]);
            }

            $symlinkPath = public_path('storage');
            $symlinkExists = is_link($symlinkPath) || file_exists($symlinkPath);

            Log::info('Stored profile photo', [
                'user_id' => $user->id,
                'stored_path' => $path,
                'disk' => 'public',
                'public_url' => $publicUrl,
                'file_exists_on_disk' => $fileExists,
                'public_storage_symlink' => $symlinkPath,
                'public_storage_symlink_exists' => $symlinkExists,
                'app_url' => config('app.url'),
            ]);

            if ($user->profile_photo_path) {
                $deleted = false;
                try {
                    $deleted = Storage::disk('public')->delete($user->profile_photo_path);
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete old profile photo', [
                        'user_id' => $user->id,
                        'old_path' => $user->profile_photo_path,
                        'error' => $e->getMessage(),
                    ]);
                }
                Log::info('Old profile photo deletion attempted', [
                    'user_id' => $user->id,
                    'old_path' => $user->profile_photo_path,
                    'deleted' => $deleted,
                ]);
            }

            $user->forceFill(['profile_photo_path' => $path])->save();
            $updatedFields[] = 'profile_photo';
        }

        AuditLog::logEvent('user.profile.updated', ['fields' => $updatedFields], $user);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateTransactionPin(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'transaction_pin' => ['required', 'digits:6'],
            'transaction_pin_confirmation' => ['required', 'same:transaction_pin'],
            'current_password' => ['required', 'current_password'],
        ];

        if ($user->transaction_pin) {
            $rules['current_transaction_pin'] = ['required', 'digits:6', function ($attribute, $value, $fail) use ($user) {
                if ($value !== (string) $user->transaction_pin) {
                    $fail('The current transaction PIN is incorrect.');
                }
            }];
        }

        $validated = $request->validate($rules);

        $user->transaction_pin = $validated['transaction_pin'];
        $user->save();

        AuditLog::logEvent('user.transaction_pin.updated', [], $user);

        return redirect()->back()->with('success', 'Transaction PIN updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        AuditLog::logEvent('user.password.updated', [], $user);

        Auth::logoutOtherDevices($validated['password']);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function logoutOtherSessions(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($validated['current_password']);

        AuditLog::logEvent('user.sessions.terminated', [], $user);

        return redirect()->back()->with('success', 'Other sessions have been logged out.');
    }
}

