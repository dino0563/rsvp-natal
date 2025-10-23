<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommsLog extends Model {
    protected $fillable = ['registration_id','channel','template_key','provider_message_id','status','error','meta'];
    protected $casts = ['meta'=>'array'];
    public function registration(){ return $this->belongsTo(Registration::class); }
}
