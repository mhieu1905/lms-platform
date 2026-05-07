<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle user login authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //get login information
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            Log::info('[AUTH] User logged in', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ]);

            UserActivityLog::create([
                'user_id' => Auth::id(),
                'action_type' => 'login',
                'device_info' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
            ]);

            if ($user->roles->contains('name', 'super_admin')) {
                $fallbackUrl = route('admin.dashboard');
            } elseif ($user->roles->contains('name', 'admin')) {
                $fallbackUrl = route('admin.dashboard');
            } elseif ($user->roles->contains('name', 'teacher')) {
                $fallbackUrl = route('admin.courses.index');
            } elseif ($user->roles->contains('name', 'student')) {
                $fallbackUrl = route('home.index');
            } else {
                Auth::logout();
                return redirect('/')
                    ->with('fail', 'No access.')
                    ->with('open_login_form', true);
            }
            $intendedUrl = $request->input('intended_url');
            return redirect($intendedUrl ?? $fallbackUrl)->with('success', 'Login successfully.');
        } else {
            return redirect('/')
                ->withInput($request->only('email'))
                ->withErrors(['wrong' => 'Email or password not true.'])
                ->with('open_login_form', true);
        }
    }

    /**
     * Log out the currently authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        Log::info('[AUTH] User logged out', [
            'user_id' => $userId,
            'ip' => $request->ip(),
            'device' => $request->header('User-Agent'),
        ]);

        UserActivityLog::create([
            'user_id' => $userId,
            'action_type' => 'logout',
            'device_info' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
        ]);

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate(); //invalidate old session and old token
            $request->session()->regenerateToken(); //make new cfrs token
        }

        return redirect('/')
            ->withCookie(cookie('XSRF-TOKEN', csrf_token(), 0, null, null, false, false))
            ->with('success', 'Logout successfully.');
    }
}
