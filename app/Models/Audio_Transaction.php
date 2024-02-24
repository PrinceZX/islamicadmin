<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio_Transaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_aiaudio_transaction';
    protected $guarded = array();

    protected $fillable = ['user_id','aiaudio_id','description','amount','payment_id','currency_code','status',];

    protected $casts = [
        'user_id' => 'integer',
        'aiaudio_id' => 'integer',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function audio()
    {
        return $this->belongsTo(Audio::class, 'aiaudio_id');
    }
}
