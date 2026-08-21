<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsManager
{
    public const CACHE_KEY = 'app.settings.cache';

    /**
     * Get all settings as a key => value array.
     */
    public static function all(): array
    {
        return collect(self::getCachedSettings())
            ->map(fn (array $entry) => self::castStoredValue($entry['value'], $entry['type']))
            ->toArray();
    }

    /**
     * Retrieve a setting value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::getCachedSettings();

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        return self::castStoredValue($settings[$key]['value'], $settings[$key]['type']);
    }

    /**
     * Persist settings.
     *
     * @param  array<string, mixed>  $values
     */
    public static function set(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => self::prepareValueForStorage($value),
                    'type' => self::determineType($value),
                ]
            );
        }

        self::flushCache();
    }

    /**
     * Remove specific settings.
     */
    public static function forget(string ...$keys): void
    {
        if (! empty($keys)) {
            Setting::query()->whereIn('key', $keys)->delete();
        }

        self::flushCache();
    }

    /**
     * Clear cached settings.
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get cached settings.
     *
     * @return array<string, array{value: ?string, type: ?string}>
     */
    protected static function getCachedSettings(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::query()
                ->get(['key', 'value', 'type'])
                ->keyBy('key')
                ->map(fn (Setting $setting) => [
                    'value' => $setting->value,
                    'type' => $setting->type,
                ])
                ->toArray();
        });
    }

    /**
     * Prepare value for storage.
     */
    protected static function prepareValueForStorage(mixed $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_THROW_ON_ERROR);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }

    /**
     * Determine the type for storage.
     */
    protected static function determineType(mixed $value): ?string
    {
        return match (true) {
            is_null($value) => null,
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value), is_object($value) => 'json',
            default => 'string',
        };
    }

    /**
     * Cast stored value back to native type.
     */
    protected static function castStoredValue(?string $value, ?string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value === '1',
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true, 512, JSON_THROW_ON_ERROR),
            default => $value,
        };
    }
}


