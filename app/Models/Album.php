<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $table = 'tbl_album';

    protected $guarded = array();

    protected $fillable = ['video_id','name','image'];

    
}
