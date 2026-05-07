<?php

namespace App\Http\Controllers\tracking;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function log(Request $request)
    {
        Log::info('[TRACKING] Incoming data: ', $request->all());

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
            'action_type' => 'required|string',
            'duration' => 'nullable|integer',
            'progress_percent' => 'nullable|numeric',
            'device_info' => 'nullable|string',
            'ip_address' => 'nullable|string|max:45',
        ]);

        $validated['ip_address'] = $request->ip();

        Log::info('[TRACKING] Validated data: ', $validated);
        UserActivityLog::create($validated);

        return response()->json(['message' => 'Activity logged successfully']);
    }
}
