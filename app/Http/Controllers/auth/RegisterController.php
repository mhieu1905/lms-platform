<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Rules\ValidTld;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*[\'-][ ]*)))*[A-Za-zÀ-ỹ]+$/'
                ],
                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                    'max:64',
                    'regex:/^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/',
                    new ValidTld,
                ],
                'password' => [
                    'required',
                    'string',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}?\/><])\S{8,}$/',
                    'confirmed'
                ],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $name = preg_replace('/\s+/', ' ', trim($request->name));
            $user = User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // Set role for user
            $role = Role::where('name', 'student')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            return redirect()->back()->with('success', 'Registration successful.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator, 'register')
                ->withInput()
                ->with('open_register_form', true);
        }
    }
}
