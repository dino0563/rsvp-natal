<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Settings
{

    protected static string $table = 'settings'; // sesuaikan

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
        $val = Cache::remember("settings:$key", 3600, function () use ($key) {
            return DB::table(static::$table)->where('key', $key)->value('value');
        });

        if ($val === null) return $default;

        // Auto-decode JSON jika memang JSON
        if (is_string($val)) {
            $trim = ltrim($val);
            if ($trim !== '' && ($trim[0] === '{' || $trim[0] === '[')) {
                $decoded = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
        }
        return $val;
    }

    public static function set(string $key, mixed $value): void
    {
        // Simpan array sebagai JSON
        $store = is_array($value) || is_object($value) ? json_encode($value) : $value;

        DB::table(static::$table)->updateOrInsert(
            ['key' => $key],
            ['value' => $store, 'updated_at' => now(), 'created_at' => now()]
        );

        Cache::forget("settings:$key");
    }

    public static function setMany(array $pairs): void
    {
        if (is_string($pairs)) {
            $decoded = json_decode($pairs, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $pairs = $decoded;
            } else {
                throw new InvalidArgumentException('setMany expects array or JSON string.');
            }
        }

        if (!is_array($pairs)) {
            throw new InvalidArgumentException('setMany expects array.');
        }

        foreach ($pairs as $k => $v) {
            static::set($k, $v);
        }
    }

    public static function forget(): void
    {
        Cache::forget('settings.all');
    }
}
