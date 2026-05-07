<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $table = 'user_activity_logs';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'action_type',
        'duration',
        'progress_percent',
        'device_info',
        'ip_address',
    ];

    protected $casts = [
        'duration' => 'integer',
        'progress_percent' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
