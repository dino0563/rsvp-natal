<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','phone','age','education_level','school','church','source',
        'ticket_code','ticket_url','qr_path','status_ticket','wa_last_status',
        'wa_last_error','wa_last_attempt_at','wa_opt_out'
    ];

    // Normalisasi semua input ke 62xxxxxxxx
    public static function normalizePhone(?string $raw): ?string
    {
        if (!$raw) return null;
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === '') return null;

        if (str_starts_with($digits,'0')) {
            $digits = '62'.substr($digits,1);
        } elseif (str_starts_with($digits,'+62')) {
            $digits = substr($digits, 1); // buang '+'
        } elseif (str_starts_with($digits,'8')) {
            $digits = '62'.$digits;
        }
        return $digits;
    }

    // Mutator: set 'phone' selalu 62...
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ['phone' => self::normalizePhone((string) $value)]
        );
    }
}
