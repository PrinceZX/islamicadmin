<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Option extends Model
{
    use HasFactory;

    protected $table = 'tbl_payment_option';
    protected $guarded = array();

    protected $fillable = [
        'name',
        'visibility',
        'is_live',
        'live_key_1',
        'live_key_2',
        'live_key_3',
        'test_key_1',
        'test_key_2',
        'test_key_3',
    ];
}
