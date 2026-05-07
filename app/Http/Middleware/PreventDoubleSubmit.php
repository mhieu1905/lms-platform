<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventDoubleSubmit
{
    /**
     * Xử lý request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userKey = Auth::check() ? Auth::id() : $request->ip();

        $lockKey = 'double_submit_lock_' . $userKey . '_' . md5($request->path());

        if (Cache::has($lockKey)) {
            return back()->with('error', 'Please do not submit the form multiple times rapidly.');
        }

        Cache::put($lockKey, true, now()->addSeconds(3));

        $response = $next($request);


        return $response;
    }
}
