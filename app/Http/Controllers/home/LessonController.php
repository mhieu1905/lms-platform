<?php

namespace App\Http\Controllers\home;

use App\Helper\UploadHelper;
use App\Models\Course;
use Illuminate\Support\Facades\Log;


use App\Models\Lesson;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show($courseId, $lessonId)
    {
        $lesson = Lesson::where('id', $lessonId)->whereHas('chapter', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with('chapter.course')->firstOrFail();
        $course = $lesson->chapter->course;

        $totalLessons = $course->lessons()->count();
        $chapters = $course->chapters()->withCount(['lessons'])->orderBy('created_at')->get();
        $currentLessonId = $lesson->id;

        $videoURL = null;
        $videoPath = UploadHelper::getVideoUploadPath($course, $lesson->chapter, $lesson->video);
        if (!empty($lesson->video) && file_exists($videoPath)) {
            $relativePath = str_replace(public_path(), '', $videoPath);
            $videoURL = asset(ltrim($relativePath, '/'));
        } else {
            $videoURL = null;
        }

        $isEnrolled = false;
        $completedLessons = [];
        $lessonIdsInCourse = [];
        $completedCount = 0;
        $isOwnerOrAdmin = false;
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $isOwnerOrAdmin = $user->id === $course->user_id || $user->hasRole('admin');

            $isEnrolled = $user->enrolledCourses()
                ->where('courses.id', $course->id)
                ->exists();

            if (!$isEnrolled && $lesson->status != 0 && !$isOwnerOrAdmin) {
                return redirect()->route('home.index')->with('error', 'You have to buy this course!!!');
            }

            if ($isEnrolled) {
                $completedLessons = $user->completedLessons()
                    ->pluck('lesson_id')
                    ->toArray();
            }
        }
        else {
            return redirect()->route('home.index')->with('error', 'You need to login!!!');
        }



        $lessonIdsInCourse = $course->chapters()
            ->with('lessons')
            ->get()
            ->flatMap(function ($chapter) {
                return $chapter->lessons->pluck('id');
            })->toArray();

        $completedCount = count(array_intersect($lessonIdsInCourse, $completedLessons));

        $allLessons = Lesson::join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $courseId)
            ->when(!$isEnrolled && !$isOwnerOrAdmin, function ($query) {
                $query->where('lessons.status', 0);
            })
            ->orderBy('chapters.created_at', 'ASC')
            ->orderBy('lessons.order', 'ASC')
            ->select('lessons.*')
            ->get()
            ->values();

        $currentIndex = $allLessons->search(fn($item) => $item->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        return view('home.lessons-details', compact('lesson', 'chapters', 'currentLessonId', 'videoURL', 'prevLesson', 'nextLesson', 'isEnrolled', 'completedLessons', 'completedCount', 'totalLessons', 'course', 'isOwnerOrAdmin'));
    }

    public function complete(Course $course, Lesson $lesson)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $alreadyCompleted = $user->completedLessons()->where('lesson_id', $lesson->id)->exists();
        if (!$alreadyCompleted) {
            $user->completedLessons()->attach($lesson->id, ['completed_at' => now()]);
        }

        $allLessons = Lesson::join('chapters', 'lessons.chapter_id', '=', 'chapters.id')
            ->where('chapters.course_id', $course->id)

            ->orderBy('chapters.created_at', 'ASC')
            ->orderBy('lessons.order', 'ASC')
            ->select('lessons.*')
            ->get()
            ->values();

        $completedLessonIds = $user->completedLessons()->pluck('lesson_id')->toArray();
        $nextUncompletedLesson = $allLessons->first(function ($item) use ($completedLessonIds) {
            return !in_array($item->id, $completedLessonIds);
        });

        if ($nextUncompletedLesson) {
            return redirect()->route('lessons.show', [
                'course' => $course->id,
                'lesson' => $nextUncompletedLesson->id
            ])->with('success', 'You have completed the lesson!');
        }

        return redirect()->route('courses.show', ['id' => $course->id])->with('success', 'You have completed all the lesson in this course!');
    }

    public function __construct()
    {
        ob_start();
    }
}
