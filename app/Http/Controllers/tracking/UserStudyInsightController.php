<?php

namespace App\Http\Controllers\tracking;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateUserStudyInsights;
use App\Models\UserActivityLog;
use App\Models\UserStudyInsight;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserStudyInsightController extends Controller
{
    protected $casts = [
        'last_activity_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    public function updateInsights()
    {
        UpdateUserStudyInsights::dispatch();

        return response()->json(['message' => 'User study insights update started']);
    }

    public function show($userId)
    {
        $insight = UserStudyInsight::where('user_id', $userId)->first();

        if (!$insight) {
            return response()->json(['message' => 'No learning data yet'], 404);
        }

        return response()->json($insight);
    }
}
