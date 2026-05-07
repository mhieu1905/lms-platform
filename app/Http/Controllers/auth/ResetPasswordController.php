<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnCallback;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form or redirect if the link is invalid/expired.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $token The password reset token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */

    public function showResetForm(Request $request, $token)
    {
        $record = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return redirect('/')->with('error', 'The password reset link is no longer valid.');
        }

        $expire = config('auth.passwords.users.expire');

        if (Carbon::parse($record->created_at)->addMinutes($expire)->isPast()) {
            return redirect('/')->with('error', 'The password reset link is no longer valid.');
        }

        return view('home.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle the password reset request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/')->with('open_login_form', true)->with('status', __($status))
            : back()->withErrors(['fail_msg' => [__($status)]]);
    }
}
