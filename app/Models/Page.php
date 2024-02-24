<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'tbl_page';
    protected $guarded = array();

    protected $fillable = ['page_name','title','description','status',];

    protected $casts = [
        'status' => 'integer',
    ];
}
