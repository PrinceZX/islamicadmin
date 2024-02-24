<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'tbl_artist';

    protected $guarded = array();

    protected $fillable = ['name','bio','address','image','status'];

    protected $casts = [
        'status' => 'integer',
    ];

    
}
