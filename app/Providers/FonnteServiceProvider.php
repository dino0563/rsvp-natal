<?php

namespace App\Providers;

use App\Services\FonnteClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class FonnteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FonnteClient::class, function ($app) {
            $cfg = config('services.fonnte');

            // Authorization Fonnte pakai header token polos, bukan Bearer
            $http = Http::baseUrl(rtrim($cfg['base'], '/').'/')
                ->withHeaders(['Authorization' => $cfg['token']])
                ->timeout($cfg['timeout'] ?? 10)
                ->connectTimeout($cfg['connect_timeout'] ?? 5)
                ->retry($cfg['retries'] ?? 3, $cfg['retry_sleep_ms'] ?? 500, throw: true);

            return new FonnteClient($http);
        });
    }

    public function boot(): void
    {
        //
    }
}
