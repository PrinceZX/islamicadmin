<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    use HasFactory;

    protected $table = 'tbl_smtp_setting';
    protected $guarded = array();

    protected $fillable = [
        'protocol',
        'host',
        'port',
        'user',
        'pass',
        'from_name',
        'from_email',
        'status',
    ];
}
