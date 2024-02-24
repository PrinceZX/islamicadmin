<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'tbl_comment';

    protected $guarded = array();

    protected $fillable = ['video_id','comment','rating','user_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function video()
    {
        return $this->belongsTo(Video::class,'video_id');
    }
}
