<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Role;
use App\Models\User;
use App\Services\RegisterTeacherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterTeacherController extends Controller
{
    /**
     * Show the form for register teacher.
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 29-08-2025
     */
    public function showForm()
    {
        $majors = Major::all();
        return view('home.register-teacher', compact('majors'));
    }

    /**
     * Handle teacher registration.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 02-09-2025
     */
    public function register(Request $request)
    {
        $validator = RegisterTeacherService::validate($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'register_teacher')
                ->withInput();
        }

        $cleaned = $validator->validated();

        if ($request->hasFile('cv_file')) {
            $cvFilePath = $request->file('cv_file')->store('uploads/cvfiles', 'public');
        } else {
            $cvFilePath = null;
        }

        $user = User::create([
            'name' => $cleaned['name'],
            'email' => $cleaned['email'],
            'password' => Hash::make($request->password),
            'cv_file' => $cvFilePath,
            'status' => 1,
            'submitted_at' => Carbon::now(),
        ]);

        // Set role for user.
        $role = Role::where('name', 'student')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        // Save major
        $majors = array_unique(array_filter($cleaned['majors'] ?? []));
        $user->majors()->sync($majors);

        if ($user) {
            return redirect()->route('home.index')
                ->with('success', 'Registration successful.');
        } else {
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.');
        }
    }
}
