<?php

namespace App\Http\Controllers;

use App\Enums\StatusTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;



class CheckinController extends Controller
{
    public function verify(Request $req)
    {
        $payload = (string) $req->input('payload', '');
        $preview = $req->boolean('preview', false);
        $confirm = $req->boolean('confirm', false);
        if ($preview && $confirm) {
            $confirm = false; // safety: kalau dua-duanya true, anggap preview
        }

        // QR payload: TKT:{CODE}|RID:{id}
        if (!preg_match('/TKT:([A-Z0-9]{6,12})\|RID:(\d+)/', $payload, $m)) {
            return response()->json([
                'ok' => false, 'status' => 'UNKNOWN', 'msg' => 'QR tidak valid.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        [$code, $rid] = [$m[1], (int) $m[2]];

        $ticket = Ticket::query()
            ->with('registration')
            ->where('code', $code)
            ->where('registration_id', $rid)
            ->first();

        if (!$ticket) {
            return response()->json([
                'ok' => false, 'status' => 'UNKNOWN', 'msg' => 'QR tidak dikenal.'
            ], Response::HTTP_OK);
        }

        $reg = $ticket->registration;

        // Sudah dipakai?
        if ($ticket->used_at) {
            return response()->json([
                'ok'    => false,
                'status'=> 'USED',
                'name'  => $reg?->name,
                'code'  => $ticket->code,
                'msg'   => 'QR sudah dipakai pada '.$ticket->used_at->timezone(config('app.timezone'))->format('d M Y H:i'),
            ], Response::HTTP_OK);
        }

        // PREVIEW: tidak ubah DB, hanya beri info
        if ($preview) {
            return response()->json([
                'ok'     => true,
                'status' => 'VALID',
                'name'   => $reg?->name,
                'code'   => $ticket->code,
            ], Response::HTTP_OK);
        }

        // CONFIRM: tandai used, tolak duplikat 10 detik
        if ($confirm) {
            $dupKey = 'checkin:dupe:'.$ticket->id;
            if (Cache::has($dupKey)) {
                return response()->json([
                    'ok' => false, 'status' => 'DUPLICATE', 'msg' => 'QR baru saja dipindai. Coba lagi sebentar.'
                ], Response::HTTP_OK);
            }

            $ticket->forceFill([
                'used_at'          => now(),
                'used_by_staff_id' => Auth::id(),
            ])->save();

            // kalau kamu menyimpan status di registrations
            if (property_exists($reg, 'status_ticket')) {
                $reg->update(['status_ticket' => StatusTicket::USED]);
            }

            // set dupe window 10 detik
            Cache::put($dupKey, 1, now()->addSeconds(10));

            return response()->json([
                'ok'     => true,
                'status' => 'USED_SET',
                'name'   => $reg?->name,
                'code'   => $ticket->code,
                'msg'    => 'Check-in berhasil.',
            ], Response::HTTP_OK);
        }

        // fallback: perlakukan sebagai preview
        return response()->json([
            'ok'     => true,
            'status' => 'VALID',
            'name'   => $reg?->name,
            'code'   => $ticket->code,
        ], Response::HTTP_OK);
    }


    // cache offline 5 ribu token
    public function cacheTokens()
    {
        $tokens = Ticket::query()->select(['code','qr_hash'])->limit(5000)->get();
        return response()->json(['data'=>$tokens]);
    }

    // sinkronisasi hasil scan yang disimpan lokal saat offline
    public function syncOffline(Request $req)
    {
        $list = $req->input('scans',[]);
        $updated = 0;

        foreach ($list as $item) {
            $hash = $item['qr_hash'] ?? null;
            if (!$hash) continue;

            $t = Ticket::where('qr_hash',$hash)->first();
            if ($t && !$t->used_at) {
                $t->forceFill([
                    'used_at'=> now(),
                    'used_by_staff_id'=> $req->user()->id,
                ])->save();
                $updated++;
            }
        }
        return response()->json(['updated'=>$updated]);
    }
}
