<?php

namespace App\Jobs;

use App\Models\Registration;
use App\Models\CommsLog;
use App\Support\Settings;
use App\Services\FonnteClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // biarin tetap implement ini buat kasus lain
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendTicketWaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $registrationId) {}

    public function handle(FonnteClient $wa): void
    {
        $r = Registration::query()->find($this->registrationId);
        if (!$r || empty($r->ticket_url)) {
            return; // belum siap atau sudah hilang
        }

        // Idempotency ringan: kalau sudah ada log sent/delivered/read 12 jam terakhir, skip
        $recent = CommsLog::query()
            ->where('registration_id', $r->id)
            ->where('channel', 'wa')
            ->where('template_key', 'ticket')
            ->whereIn('status', ['sent', 'delivered', 'read'])
            ->where('created_at', '>=', now()->subHours(12))
            ->exists();

        if ($recent) {
            return;
        }

        $s = Settings::all();

        // Pastikan APP_URL kamu real dan pakai https kalau bisa
        $ticketUrl = $r->ticket_url; // misal sudah "https://rsvp-natal.test/t/W0X6MNMV"

        // Taruh link di baris sendiri biar auto-link
        $text = sprintf(
            "🎄 *%s* 🎄\n" .
                "Halo %s! _Tiketmu sudah siap._ ✨\n\n" .
                "🧾 *E-Ticket:*\n%s\n\n" .
                "🗓️ *Tanggal:* %s\n" .
                "🕕 *Waktu:* %s\n" .
                "📍 *Tempat:* %s\n" .
                "👗 *Dresscode:* %s\n\n" .
                "> _Datang 15 menit lebih awal untuk check-in QR dan nikmati suasana dari awal._\n\n" .
                "*Sampai ketemu di sana!* 🎉",
            $s->event_name ?? 'Natal Teens X Youth 2025',
            $r->name ?? '-',
            $ticketUrl ?? '-',
            ($s->event_date ?? ($s->date ?? '-')),
            $s->gate_time ?? '-',
            ($s->venue ?? ($s->location ?? '-')),
            $s->dresscode ?? '-'
        );


        try {
            $resp = $wa->sendText($r->phone, $text);
            $providerId = $resp['id'][0] ?? ($resp['id'] ?? ($resp['message_id'] ?? null));

            CommsLog::create([
                'registration_id' => $r->id,
                'channel' => 'wa',
                'template_key' => 'ticket',
                'provider_message_id' => $providerId,
                'status' => 'sent',
                'meta' => ['response' => $resp],
            ]);

            $r->update(['wa_last_status' => 'sent', 'wa_last_error' => null, 'wa_last_attempt_at' => now()]);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            CommsLog::create([
                'registration_id' => $r->id,
                'channel' => 'wa',
                'template_key' => 'ticket',
                'status' => 'failed',
                'error' => 'HTTP ' . $e->response->status() . ' ' . $e->getMessage(),
                'meta' => ['body' => $e->response->json() ?? $e->response->body()],
            ]);
            $r->update(['wa_last_status' => 'failed', 'wa_last_error' => $e->getMessage(), 'wa_last_attempt_at' => now()]);
        } catch (\Throwable $e) {
            CommsLog::create([
                'registration_id' => $r->id,
                'channel' => 'wa',
                'template_key' => 'ticket',
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
            $r->update(['wa_last_status' => 'failed', 'wa_last_error' => $e->getMessage(), 'wa_last_attempt_at' => now()]);
        }
    }
}
