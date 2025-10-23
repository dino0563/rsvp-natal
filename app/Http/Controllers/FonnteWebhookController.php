<?php

namespace App\Http\Controllers;

use App\Enums\WaStatus;
use App\Models\CommsLog;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FonnteWebhookController extends Controller
{
    public function handle(Request $req)
    {
        // 0) Verifikasi secret (header atau body)
        $expected = (string) config('services.fonnte.callback_token');
        $incoming = (string) (
            $req->header('X-Fonnte-Secret')
            ?? $req->header('X-Callback-Token')
            ?? $req->input('secret')
        );

        abort_unless($incoming && hash_equals($expected, $incoming), 401, 'Invalid webhook secret');

        // 1) Ambil payload (support form-data & JSON)
        $p = $req->all();

        // 2) Ambil nomor lalu normalisasi -> 62xxxxxxxx
        $targetRaw = $p['phone'] ?? $p['target'] ?? $p['sender'] ?? $p['from'] ?? '';
        $phone = method_exists(Registration::class, 'toE164')
            ? Registration::toE164((string) $targetRaw)
            : self::toE164Fallback((string) $targetRaw);

        if (! $phone) {
            Log::info('fonnte_webhook_no_phone', ['payload' => $p]);
            return $this->respond($req, [
                'ok'   => true,
                'note' => 'No phone in payload; ignored',
            ]);
        }

        // 3) Status, id, reason (Fonnte suka variasi key)
        $statusRaw   = strtolower((string) ($p['status'] ?? 'unknown'));
        $providerId  = (string) ($p['id'] ?? $p['message_id'] ?? $p['msg_id'] ?? '');
        $errorReason = $p['reason'] ?? $p['detail'] ?? $p['error'] ?? null;

        // 4) Map status ke enum kamu
        $map = [
            'sent'      => WaStatus::SENT,
            'delivered' => WaStatus::DELIVERED,
            'read'      => WaStatus::READ,
            'failed'    => WaStatus::FAILED,
            'blocked'   => WaStatus::BLOCKED,
            'unknown'   => WaStatus::UNKNOWN,
        ];
        $waEnum = $map[$statusRaw] ?? WaStatus::UNKNOWN;

        // 5) Cari registrasi pakai kolom `phone` (kamu bilang sudah E.164)
        $reg = Registration::where('phone', $phone)->first();

        // 6) Kalau ketemu, update status terakhir
        if ($reg) {
            $reg->update(['wa_last_status' => $waEnum]);
        }

        // 7) Sinkronisasi CommsLog tanpa bikin registration_id NULL
        if ($reg) {
            // Ada registration, aman bikin/ubah log
            if ($providerId) {
                $updated = CommsLog::where('provider_message_id', $providerId)->update([
                    'registration_id'     => $reg->id,
                    'status'              => $statusRaw,
                    'error'               => $errorReason,
                    'meta'                => ['callback' => $p],
                ]);

                if (! $updated) {
                    CommsLog::create([
                        'registration_id'     => $reg->id,
                        'channel'             => 'wa',
                        'template_key'        => 'callback',
                        'provider_message_id' => $providerId,
                        'status'              => $statusRaw,
                        'error'               => $errorReason,
                        'meta'                => ['callback' => $p],
                    ]);
                }
            } else {
                // Gak ada providerId, tetap catat demi audit
                CommsLog::create([
                    'registration_id'     => $reg->id,
                    'channel'             => 'wa',
                    'template_key'        => 'callback',
                    'provider_message_id' => null,
                    'status'              => $statusRaw,
                    'error'               => $errorReason,
                    'meta'                => ['callback' => $p],
                ]);
            }
        } else {
            // Tidak ada registration: JANGAN create CommsLog agar tidak NULL
            // Tetap log ke file biar jejaknya ada.
            Log::info('fonnte_webhook_unmatched_registration', [
                'phone'         => $phone,
                'provider_id'   => $providerId,
                'status'        => $statusRaw,
                'payload'       => $p,
            ]);
        }

        // 8) Respons
        $statusValue = ($waEnum instanceof \BackedEnum) ? $waEnum->value : (string) $waEnum;

        return $this->respond($req, [
            'ok'                      => true,
            'matched_registration_id' => $reg?->id,
            'phone'                   => $phone,
            'provider_message_id'     => $providerId,
            'status_raw'              => $statusRaw,
            'status_enum'             => $statusValue,
        ]);
    }

    private function respond(Request $req, array $data)
    {
        $debug = $req->boolean('debug') || config('app.debug');
        return $debug ? response()->json($data) : response()->noContent();
    }

    // Cadangan kalau Model belum punya helper
    private static function toE164Fallback(string $raw): ?string
    {
        $d = preg_replace('/\D+/', '', $raw);
        if ($d === '') return null;
        if (str_starts_with($d, '0')) {
            $d = '62' . substr($d, 1);
        } elseif (! str_starts_with($d, '62')) {
            if (str_starts_with($d, '8')) $d = '62' . $d;
        }
        return $d;
    }
}
