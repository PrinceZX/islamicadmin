<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'tbl_language';

    protected $guarded = array();

    protected $fillable = ['name','image','status',];

    protected $casts = [
        'status' => 'integer',
    ];
}
