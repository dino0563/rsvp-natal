<?php
namespace App\Services;

use App\Support\Settings;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class FonnteClient
{
    protected function http(): PendingRequest
    {
        $base  = rtrim(config('services.fonnte.base', 'https://api.fonnte.com'), '/');
        $token = Settings::get('fonnte_token');

        if (empty($token)) {
            throw new \RuntimeException('Fonnte token kosong. Isi di Pengaturan.');
        }

        return Http::baseUrl($base)
            ->asForm()                    // Fonnte minta form
            ->timeout((int) config('services.fonnte.timeout', 15))
            ->connectTimeout((int) config('services.fonnte.connect_timeout', 5))
            ->withHeaders(['Authorization' => $token]);
    }

    public function sendText(string $phone, string $message): array
    {
        // countryCode=0: jangan diutak-atik, kita sudah kirim 62…
        $payload = [
            'target'      => (string) $phone,
            'message'     => $message,
            'countryCode' => '0',
        ];

        $res  = $this->http()->post('send', $payload);
        $json = $res->json() ?? [];

        if (!$res->successful()) {
            throw new RequestException($res);
        }
        if (!($json['status'] ?? false)) {
            // Fonnte balas 200 tapi status:false -> alasan di 'reason'
            throw new \RuntimeException('Fonnte rejected: '.($json['reason'] ?? 'unknown'));
        }

        return $json;
    }

    public function sendImage(string $phone, string $imageUrl, string $caption = ''): array
    {
        // Per dokumen: paramnya 'url' (bukan 'image')
        $payload = [
            'target'      => (string) $phone,
            'message'     => $caption,
            'url'         => $imageUrl,    // file publik, bukan halaman
            'countryCode' => '0',
        ];

        $res  = $this->http()->post('send', $payload);
        $json = $res->json() ?? [];

        if (!$res->successful()) {
            throw new RequestException($res);
        }
        if (!($json['status'] ?? false)) {
            throw new \RuntimeException('Fonnte rejected: '.($json['reason'] ?? 'unknown'));
        }

        return $json;
    }
}
