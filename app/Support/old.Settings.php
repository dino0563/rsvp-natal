<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Settings
{
    public static function all(): object
    {
        // cache 10 menit (Laravel 12: pakai DateTime untuk TTL)
        $pairs = Cache::remember('settings.all', now()->addMinutes(10), function () {
            return DB::table('settings')->pluck('value', 'key')->toArray();
        });

        return (object) $pairs;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();
        return $all->$key ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $value = self::normalizeValue($value);
        DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        self::forget();
    }

    public static function setMany(array $keyValues): void
    {
        foreach ($keyValues as $k => $v) {
            $v = self::normalizeValue($v);
            DB::table('settings')->updateOrInsert(['key' => $k], ['value' => $v]);
        }
        self::forget();
    }

    public static function forget(): void
    {
        Cache::forget('settings.all');
    }

    protected static function normalizeValue(mixed $v): ?string
{
    // simpan false sebagai null; biarkan string kosong tetap ''
    if ($v === false) return null;

    if (is_array($v)) {
        if ($v === []) return null;
        $first = reset($v);
        return is_scalar($first)
            ? (string) $first
            : json_encode($first, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    if (is_object($v)) {
        return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    return is_null($v) ? null : (string) $v;
}

}
