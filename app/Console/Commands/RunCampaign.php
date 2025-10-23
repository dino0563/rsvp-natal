<?php
// app/Console/Commands/RunCampaign.php
namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Registration;
use App\Services\FonnteClient;
use App\Models\CommsLog;
use Illuminate\Console\Command;

class RunCampaign extends Command
{
    protected $signature = 'campaign:run {--id=} {--dry}';
    protected $description = 'Jalankan campaign text blast';

    public function handle(): int
    {
        $id = (int) $this->option('id');
        $c  = Campaign::findOrFail($id);

        $tps = max((int) $c->throttle_per_second, 1);
        $sleepBetween = (int) floor(1_000_000 / $tps); // microseconds
        $sleepPerMsg  = (int) $c->delay_ms;

        $aud = Registration::query()
            ->leftJoin('tickets','tickets.registration_id','=','registrations.id')
            ->where('status_ticket','generated')
            ->whereNull('tickets.used_at')
            ->where('wa_opt_out', false)
            ->whereNotIn('wa_last_status',['blocked'])
            ->select('registrations.*')
            ->orderBy('registrations.id');

        $wa = app(FonnteClient::class);
        $sent = 0;

        foreach ($aud->cursor() as $r) {
            $msg = $c->renderText($r);

            if ($this->option('dry')) {
                $this->line("DRY → {$r->phone}: ".str_replace("\n"," | ",$msg));
            } else {
                try {
                    $resp = $wa->sendText($r->phone, $msg);
                    CommsLog::create([
                        'registration_id'     => $r->id,
                        'channel'             => 'wa',
                        'template_key'        => 'campaign:'.$c->id,
                        'provider_message_id' => $resp['id'][0] ?? $resp['id'] ?? null,
                        'status'              => 'sent',
                        'meta'                => ['campaign_id'=>$c->id,'response'=>$resp],
                    ]);
                    $r->update(['wa_last_status'=>'sent','wa_last_error'=>null,'wa_last_attempt_at'=>now()]);
                } catch (\Throwable $e) {
                    CommsLog::create([
                        'registration_id'=>$r->id,
                        'channel'=>'wa',
                        'template_key'=>'campaign:'.$c->id,
                        'status'=>'failed',
                        'error'=>$e->getMessage(),
                    ]);
                    $r->update(['wa_last_status'=>'failed','wa_last_error'=>$e->getMessage(),'wa_last_attempt_at'=>now()]);
                }

                if ($sleepBetween > 0) usleep($sleepBetween);
                if ($sleepPerMsg > 0)  usleep($sleepPerMsg * 1000);
            }

            $sent++;
        }

        $this->info("Campaign #{$c->id} ({$c->name}) selesai. Dikirim: {$sent}");
        return self::SUCCESS;
    }
}
