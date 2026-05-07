<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;

class GetCourseController extends Controller
{
    public function index()
    {
        $courses = Course::select('id', 'title', 'image', 'duration', 'language', 'regular_price', 'sale_price', 'category_id', 'level_id', 'description', 'user_id')
            ->with([
                'level:id,name',
                'category:id,name',
                'user:id,name'
            ])
            ->where('status', config('settings.status.public'))
            ->get();

        $courses = $courses->map(function ($course){
            $course->makeHidden(['category_id', 'level_id', 'user_id']);
            $course->image = url("storage/{$course->image}");
            $course->link = url("courses/{$course->id}");
            $course->total_enrollments = $course->enrolledUsers()->count();
            return $course;
        });

        return response()->json($courses);
    }
}
