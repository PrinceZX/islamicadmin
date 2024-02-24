<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'tbl_like';
    protected $guarded = array();

    protected $fillable = ['user_id','video_id','status'];
}
