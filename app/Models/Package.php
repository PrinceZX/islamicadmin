<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'tbl_package';
    protected $guarded = array();

    protected $fillable = ['name','price','currency_type', 'android_product_package','ios_product_package','type','time','status','image'];

    protected $casts = [
        'price' => 'integer',
        'status' => 'integer',
    ];
}
