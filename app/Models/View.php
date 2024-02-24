<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $table = 'tbl_view';
    protected $guarded = array();

    protected $fillable = ['user_id','video_id','status','type'];
}
