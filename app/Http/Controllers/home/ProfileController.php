<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {

        if (!Auth::check()) {
            return redirect()->route('home.index')->with('error', 'You must be logged in to view your profile.');
        } else {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $enrolledCourse = $user->enrolledCourses()
                ->withPivot('enrolled_at')
                ->withCount('lessons')
                ->get();
            $enrolledCourseIds = $enrolledCourse->pluck('id')->toArray();
            $enrolledCourseCount = $enrolledCourse->count();

            $completedCourse = $user->completedCourses()
                ->withPivot('completed_at')
                ->get();
            $completedCourseIds = $completedCourse->pluck('id')->toArray();
            $completedCourseCount = $completedCourse->count();

            $progresCrouseIds = array_diff($enrolledCourseIds, $completedCourseIds);
            $inProgressCourses = Course::whereIn('id', $progresCrouseIds)->get();
            $progresCrouseCount = $inProgressCourses->count();

            $progressMap = [];
            $completionTimeMap = [];
            $expirationTime = [];

            foreach ($enrolledCourse as $course) {
                $totalLesson = $course->lessons_count;
                $completedLessonsCount = $user->completedLessons()
                    ->join('chapters', 'chapters.id', '=', 'lessons.chapter_id')
                    ->where('chapters.course_id', '=', $course->id)
                    ->count();
                $progressPercent = $totalLesson > 0
                    ? round(($completedLessonsCount / $totalLesson) * 100, 2)
                    : 0;

                $progressMap[$course->id] = $progressPercent;

                $enrollAt = Carbon::parse($course->created_at);
                $durationMonths = (int) $course->duration;
                $expirationTime = $enrollAt->copy()->addMonths($durationMonths);

                $completedCourseData = $completedCourse->where('id', '=', $course->id)->first();
                if ($completedCourseData && $completedCourseData->pivot) {
                    $completionTime = Carbon::parse($completedCourseData->pivot->completed_at);
                    $completionTimeMap[$course->id] = $completionTime;
                }
            }
        }


        return view('home.profile',  compact('user', 'enrolledCourse', 'completedCourse', 'inProgressCourses', 'progresCrouseCount', 'enrolledCourseCount', 'completedCourseCount', 'progressMap', 'expirationTime', 'completionTimeMap'));
    }

    /**
     * Show profile details of user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 08-09-2025
     */
    public function showProfileDetails()
    {

        if (!Auth::check()) {
            return redirect()->route('home.index')->with('error', 'You must be logged in to view your profile.');
        } else {
            /** @var \App\Models\User $user */
            $user = Auth::user();
        }

        return response()
                ->view('home.profile-details', compact('user'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Update profile of user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 09-09-2025
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home.index')->with('error', 'You must be logged in to view your profile.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*[\'-][ ]*)))*[A-Za-zÀ-ỹ]+$/'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^0\d{9}$/',
                'unique:users,phone,' . $user->id
            ],
            'address' => [
                'nullable',
                'string',
                'max:150',
                'regex:/^.{5,150}$/'
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048'
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'profile_edit')
                ->withInput();
        }

        $validatedData = $request->validate($rules);

        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'] ?? null;
        $user->address = $validatedData['address'] ?? null;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }

            $path = $file->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.details')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show form change password
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 09-09-2025
     */
    public function showChangePasswordForm()
    {
        if (!Auth::check()) {
            return redirect()->route('home.index')->with('error', 'You must be logged in to view your profile.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('home.change-password', compact('user'));
    }

    /**
     * Change password
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 09-09-2025
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password_old' => ['required'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}\?\/><])\S{8,}$/',
                'confirmed',
            ],
        ], [
            'password_old.required' => 'Old password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check old password
        if (!Hash::check($request->password_old, $user->password)) {
            return back()->withErrors(['password_old' => 'Old password is incorrect.'], 'change_password')->withInput();
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'New password cannot be the same as the old password.'], 'change_password')->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.details')->with('success', 'Password changed successfully.');
    }
}
