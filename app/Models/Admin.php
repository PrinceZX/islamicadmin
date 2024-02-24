<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_admin';

    protected $guarded = array();

    protected $fillable = [
        'user_name',
        'email',
        'permissions_role',
        'status',
        'type',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
