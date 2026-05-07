<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed $permission
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home.index')->with('error', 'You need to login to access.');
        }

        /** @var \App\Models\User $user */
        $user->load('roles.permissions');
        
        if (!$user->hasPermission($permission)) {
            return redirect()->back()->with('error', 'You do not have permission to access to this page.');
        }

        return $next($request);
    }
}
