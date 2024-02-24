<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $table = 'tbl_download';
    protected $guarded = array();

    protected $casts = ['id','user_id','video_id','status'];

   
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class,'video_id');
    }
}
