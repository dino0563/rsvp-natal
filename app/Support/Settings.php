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
        DB::table('settings')->updateOrInsert(['key' => $key], ['value' => $value]);
        self::forget();
    }

    public static function setMany(array $keyValues): void
    {
        foreach ($keyValues as $k => $v) {
            DB::table('settings')->updateOrInsert(['key' => $k], ['value' => $v]);
        }
        self::forget();
    }

    public static function forget(): void
    {
        Cache::forget('settings.all');
    }
}
