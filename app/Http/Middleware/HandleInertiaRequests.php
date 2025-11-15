<?php

namespace App\Http\Middleware;

use App\Support\SettingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        // Always refresh user from database to get latest data (especially created_at)
        if ($user) {
            $user = $user->fresh();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'supportEmail' => SettingsManager::get('support_email', env('MAIL_SUPPORT')),
            'appSettings' => [
                'siteName' => SettingsManager::get('site_name', config('app.name')),
                'siteEmail' => SettingsManager::get('site_email', config('mail.from.address')),
                'supportEmail' => SettingsManager::get('support_email', env('MAIL_SUPPORT')),
                'logoUrl' => $this->resolveLogoUrl(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }

    protected function resolveLogoUrl(): ?string
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
