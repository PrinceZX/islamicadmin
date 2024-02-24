<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification';
    protected $guarded = array();

    protected $fillable = ['title','from_user_id','user_id','video_id','type','message','image',];
}
