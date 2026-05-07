<?php

namespace App\Http\Controllers\home;

use App\Helper\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\CompletedLesson;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Level;
use App\Services\CourseService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function show($id)
    {
        // Get all categories And Count 
        $categories = Category::withCount('courses')
            ->having('courses_count', '>', 0)
            ->orderBy('courses_count', 'DESC')
            ->limit(config('settings.course_details.categories'))->get();
        // Get Courses and count with lesssons
        $course = Course::with('user')->where('status', 1)->withCount('lessons')->findOrFail($id);
        // Get chapter in $Course
        $chapters = $course->chapters()->withCount('lessons')->orderBy('created_at')->get();

        $enrolledUserCount = $course->enrolledUsers()->count();

        $youMayLike = Course::withCount('enrolledUsers', 'lessons')
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('status', 1)
            ->orderByDesc('enrolled_users_count')
            ->limit(config('settings.course_details.you_may_like'))
            ->get();

        $latestCourses = Course::orderBy('created_at', 'DESC')->where('status', 1)->limit(config('settings.course_details.latest_courses'))->get();

        $firstLessonWithVideo = DB::table('lessons')
            ->join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $id)
            ->whereNotNull('lessons.video')
            ->where('lessons.video', '!=', '')
            ->orderBy('chapters.created_at')
            ->orderBy('lessons.order')
            ->select('lessons.*', 'chapters.id as chapter_id', 'chapters.title as chapter_title')
            ->first();

        $videoURL = null;

        if ($firstLessonWithVideo) {
            $firstChapter = $chapters->where('id', $firstLessonWithVideo->chapter_id)->first();

            if ($firstChapter) {
                $videoPath  = UploadHelper::getVideoUploadPath($course, $firstChapter, $firstLessonWithVideo->video);
                if (file_exists($videoPath)) {
                    $relativePath = str_replace(public_path(), '', $videoPath);
                    $videoURL = asset(ltrim($relativePath, '/'));
                } else {
                    $videoURL = null;
                }
            }
        }

        $isEnrolled = false;
        $completedLessons = [];
        $lessonIdsInCourse = [];
        $completedCount = 0;
        $isCourseCompleted = false;
        $isOwnerOrAdmin = false;
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $isEnrolled = $user->enrolledCourses()
                ->where('courses.id', $course->id)
                ->exists();

            if ($isEnrolled) {
                $completedLessons = $user->completedLessons()
                    ->pluck('lesson_id')
                    ->toArray();
            }
            $isOwnerOrAdmin = $user->id === $course->user_id || $user->hasRole('admin');

            $isCourseCompleted = $user->hasCompletedCourse($course->id);
        }

        $lessonIdsInCourse = $course->chapters()
            ->with('lessons')
            ->get()
            ->flatMap(function ($chapter) {
                return $chapter->lessons->pluck('id');
            })->toArray();

        $completedCount = count(array_intersect($lessonIdsInCourse, $completedLessons));

        $allLessons = Lesson::join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $course->id)
            ->orderBy('chapters.created_at', 'ASC')
            ->orderBy('lessons.order', 'ASC')
            ->select('lessons.*')
            ->get()
            ->values();

        $nextLessonToContinue = null;
        foreach ($allLessons as $lesson) {
            if (!in_array($lesson->id, $completedLessons)) {
                $nextLessonToContinue = $lesson;
                break;
            }
        }

        return view('home.courses-details', compact('course', 'categories', 'chapters', 'youMayLike', 'latestCourses', 'videoURL', 'isEnrolled', 'completedLessons', 'completedCount', 'enrolledUserCount', 'nextLessonToContinue', 'isCourseCompleted', 'isOwnerOrAdmin'));
    }

    public function enrollFree(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isOwnerOrAdmin = $user->id === $course->user_id || $user->hasRole('admin');

        if ($isOwnerOrAdmin) {
            $user->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);
            return redirect()->back()->with('success', 'You have enrolled in the course successfully.');
        }

        $alreadyEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->with('error', 'You have already enrolled this.');
        }

        if ($course->regular_price > 0 && ($course->sale_price === null || $course->sale_price > 0)) {
            return redirect()->back()->with('error', 'This course is not free');
        }

        $user->enrolledCourses()->attach($course->id, ['enrolled_at' => now()]);

        return redirect()->back()->with('success', 'You have enrolled in the course successfully.');
    }

    public function finish(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasCompletedCourse($course->id)) {
            $user->completedCourses()->attach($course->id, ['completed_at' => now()]);
        }

        return redirect()->route('courses.show', ['id' => $course->id])->with('success', 'You have completed the course successfully.');
    }

    public function retake(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasCompletedCourse($course->id)) {
            $user->completedCourses()->detach($course->id);
            CompletedLesson::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->delete();
        }

        return redirect()->back()->with('success', 'You have retaken the course. Progress has been reset.');
    }
}
