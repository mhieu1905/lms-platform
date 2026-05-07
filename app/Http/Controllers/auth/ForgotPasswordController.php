<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Rules\ValidTld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Show the password reset request form.
     *
     * Displays the form where users can enter their email to request a password reset link.
     *
     * @return \Illuminate\View\View The view for the forgot-password page.
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function showLinkRequestForm()
    {
        return view('home.forgot-password');
    }

    /**
     * Handle sending a password reset link to the user's email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
                'max:64',
                'regex:/^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/',
                new ValidTld,
            ],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __($status))
                : back()->withErrors(['fail_msg' => __($status)]);
    }
}
