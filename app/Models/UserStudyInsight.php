<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStudyInsight extends Model
{
    use HasFactory;

    protected $table = 'user_study_insights';

    protected $fillable = [
        'user_id',
        'total_courses',
        'completed_courses',
        'avg_progress',
        'last_activity_at',
        'inactive_days',
        'risk_score',
        'last_reminder_sent_at',
        'email_status',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'avg_progress' => 'float',
        'risk_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
