<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'tbl_bookmark';
    protected $guarded = array();

    public function video()
    {
        return $this->belongsTo(Video::class,'video_id');
    }

    protected $casts = ['id','user_id' ,'video_id','status'];
}
