<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $fillable = ['registration_id','code','qr_hash','used_at','used_by_staff_id'];
    protected $casts = ['used_at'=>'datetime'];

    public function registration(){ return $this->belongsTo(Registration::class); }
    public function staff(){ return $this->belongsTo(User::class, 'used_by_staff_id'); }
}
