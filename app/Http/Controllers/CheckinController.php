<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckinController extends Controller
{
    public function verify(Request $req)
    {
        $payload = $req->string('payload');
        $hash = hash('sha256', $payload);

        // anti-dupe 10 detik
        $key = 'scan-dupe:'.$hash;
        if (Cache::has($key)) {
            return response()->json(['ok'=>false,'msg'=>'Sudah dipakai (duplikat)'], 409);
        }

        $ticket = Ticket::where('qr_hash',$hash)->first();
        if (!$ticket) return response()->json(['ok'=>false,'msg'=>'Tidak dikenal'],404);

        if ($ticket->used_at) return response()->json(['ok'=>false,'msg'=>'Sudah dipakai'],409);

        $ticket->forceFill([
            'used_at'=> now(),
            'used_by_staff_id'=> $req->user()->id,
        ])->save();

        Cache::put($key, 1, 10);

        return response()->json(['ok'=>true,'name'=>$ticket->registration->name]);
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
