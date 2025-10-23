<?php

namespace App\Observers;

use App\Enums\StatusTicket;
use App\Jobs\SendTicketWaJob;
use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\GdImageBackEnd;

class RegistrationObserver
{
    public function creating(Registration $r): void
    {
        // unique phone ditangani di DB + form request, santai.
    }

    public function created(Registration $r): void
    {
        $code    = $this->generateUniqueCode();
        $payload = 'TKT:' . $code . '|RID:' . $r->id; // jangan taruh data sensitif
        $qrHash  = hash('sha256', $payload);

        $ticketUrl = rtrim(config('app.url'), '/') . '/t/' . $code;

        // Render QR via bacon/bacon-qr-code v2
        $png = $this->makeQrPng($payload, size: 540, margin: 1);

        $qrPath = "tickets/{$code}.png";
        Storage::disk('public')->put($qrPath, $png);

        Ticket::create([
            'registration_id' => $r->id,
            'code'            => $code,
            'qr_hash'         => $qrHash,
        ]);

        $r->update([
            'ticket_code'   => $code,
            'ticket_url'    => $ticketUrl,
            'qr_path'       => $qrPath,
            'status_ticket' => StatusTicket::GENERATED,
        ]);

        // dispatch(new SendTicketWaJob($r->id));
    }

    protected function makeQrPng(string $payload, int $size = 540, int $margin = 1): string
    {
        // Pilih backend: Imagick kalau ada, kalau tidak coba GD
        $backend = null;

        if (extension_loaded('imagick')) {
            $backend = new ImagickImageBackEnd();
        } elseif (function_exists('imagecreatetruecolor')) {
            // fungsi gd tersedia
            $backend = new GdImageBackEnd();
        }

        if (!$backend) {
            throw new \RuntimeException('Tidak ada backend gambar. Aktifkan ekstensi imagick atau gd.');
        }

        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            $backend
        );

        $writer = new Writer($renderer);
        return $writer->writeString($payload); // hasil PNG binary
    }

    protected function generateUniqueCode(int $len = 8): string
    {
        do {
            $code = strtoupper(Str::random($len));
        } while (Ticket::where('code', $code)->exists());

        return $code;
    }
}
