<?php

namespace App\Http\Controllers;

use App\Enums\StatusTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\GdImageBackEnd;

class TicketController extends Controller
{
    public function show(Request $request, string $code)
    {
        $code = strtoupper($code);
        if (!preg_match('/^[A-Z0-9]{6,12}$/', $code)) {
            abort(404);
        }

        $ticket = Ticket::query()->with('registration')->where('code', $code)->firstOrFail();
        $reg = $ticket->registration;

        if ($reg->status_ticket === StatusTicket::REVOKED) {
            return response()->view('ticket.revoked', compact('ticket', 'reg'))->setStatusCode(410);
        }

        if (!$reg->qr_path || !Storage::disk('public')->exists($reg->qr_path)) {
            $this->regenerateQr($ticket);
            $reg->refresh();
        }

        $settings = $this->settings(); // <= pakai method lokal, bukan fungsi global
        $qrUrl = route('ticket.qr', $ticket->code);

        if ($request->wantsJson()) {
            return response()->json([
                'event_name' => $settings->event_name ?? null,
                'name'       => $reg->name,
                'code'       => $ticket->code,
                'ticket_url' => $reg->ticket_url,
                'qr_url'     => $qrUrl,
                'used_at'    => optional($ticket->used_at)->toIso8601String(),
            ]);
        }

        return response()
            ->view('ticket.show', compact('ticket', 'reg', 'settings', 'qrUrl'))
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function image(Request $request, string $code)
    {
        $code = strtoupper($code);
        $ticket = Ticket::with('registration')->where('code', $code)->firstOrFail();
        $reg = $ticket->registration;

        if (!$reg->qr_path || !Storage::disk('public')->exists($reg->qr_path)) {
            $this->regenerateQr($ticket);
            $reg->refresh();
        }

        $path = $reg->qr_path;
        $lastModified = Storage::disk('public')->lastModified($path);
        $etag = md5($path.$lastModified);

        if ($request->headers->get('If-None-Match') === $etag) {
            return response('', 304)
                ->header('ETag', $etag)
                ->header('Cache-Control', 'public, max-age=86400');
        }

        $stream = Storage::disk('public')->readStream($path);

        return Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
            'ETag'          => $etag,
        ]);
    }

    public function regenerateQr(Ticket $ticket): void
    {
        $payload = 'TKT:'.$ticket->code.'|RID:'.$ticket->registration_id;

        $backend = extension_loaded('imagick')
            ? new ImagickImageBackEnd()
            : (function_exists('imagecreatetruecolor') ? new GdImageBackEnd() : null);

        if (!$backend) {
            throw new \RuntimeException('QR backend tidak tersedia. Aktifkan ekstensi imagick atau gd.');
        }

        $renderer = new ImageRenderer(
            new RendererStyle(540, 1),
            $backend
        );

        $writer = new Writer($renderer);
        $png = $writer->writeString($payload);

        $qrPath = "tickets/{$ticket->code}.png";
        Storage::disk('public')->put($qrPath, $png);

        $ticket->registration->update(['qr_path' => $qrPath]);
    }

    // Ambil settings sekali, cache di memory request
    private function settings(): object
    {
        static $cache;
        if ($cache) return $cache;

        $pairs = DB::table('settings')->pluck('value','key')->toArray();
        return $cache = (object) $pairs;
    }
}
