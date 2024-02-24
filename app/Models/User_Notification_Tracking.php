<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Notification_Tracking extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_notification_tracking';
    protected $guarded = array();

    protected $fillable = ['user_id','notification_id',];

    protected $casts = [
        'user_id' => 'integer',
        'notification_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}
