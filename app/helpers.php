<?php

use App\Models\Setting;

function settings(): object {
    static $cache;
    if ($cache) return $cache;

    $pairs = Setting::query()->pluck('value','key')->toArray();
    return $cache = (object) $pairs;
}
