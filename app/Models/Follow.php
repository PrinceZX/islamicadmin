<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'tbl_follow';
    protected $guarded = array();

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'to_user_id' => 'string',
        'status' => 'string',
    ];

    public function to_user()
    {
        return $this->belongsTo(User::class,'to_user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
