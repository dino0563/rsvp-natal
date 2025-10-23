<?php
// app/Models/Campaign.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Settings;
use App\Models\Registration;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','key','text_template','throttle_per_second','delay_ms','scheduled_at','status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'throttle_per_second' => 'integer',
        'delay_ms' => 'integer',
    ];

    // auto-set key dari name kalau key kosong
    protected static function booted(): void
    {
        static::saving(function (self $c) {
            if (empty($c->key) && !empty($c->name)) {
                $c->key = Str::slug($c->name);
            }
        });
    }

    public function renderText(Registration $r): string
    {
        $s = Settings::all();
        $map = [
            '{event_name}' => (string) ($s->event_name ?? ''),
            '{nama}'       => (string) $r->name,
            '{ticket_url}' => (string) $r->ticket_url,
            '{gate_time}'  => (string) ($s->gate_time ?? ''),
            '{dresscode}'  => (string) ($s->dresscode ?? ''),
            '{code}'       => (string) $r->ticket_code,
            '{phone}'      => (string) $r->phone,
        ];
        return strtr((string) $this->text_template, $map);
    }
}
