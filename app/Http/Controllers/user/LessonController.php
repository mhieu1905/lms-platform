<?php

namespace App\Http\Controllers\user;

use App\Helper\UploadHelper;
use Illuminate\Support\Facades\Log;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Footer;
use App\Models\Lesson;
use App\Services\FormValidationService;
use App\Services\LogService;
use Illuminate\Http\Request;
use App\Services\LessonService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $data = Lesson::sortable(['id' => 'desc'])->paginate(config('settings.pagination.per_page'));
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $data = Lesson::whereHas('chapter.course', function ($query)
            use ($user) {
                $query->where('user_id', '=', $user->id);
            })  
                ->with(['chapter', 'chapter.course'])
                ->sortable(['id' => 'desc'])
                ->paginate(config('settings.pagination.per_page'));
        }
        return view('admin.lessons.index', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('title', 'DESC')->get();
        $chaps = Chapter::orderBy('title', 'DESC')->get();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $courses = Course::where('user_id', '=', $user->id)->get();
            $chaps = Chapter::whereHas('course', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })
                ->orderByDesc('id')
                ->get();
        }
        return view('admin.lessons.create', compact('chaps', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(FormValidationService::lessonRules($request));

        $course = Course::findOrFail($request->course_id);
        $chapter = Chapter::findOrFail($request->chapter_id);

        $excludeClean = ['content', 'video'];
        $cleanData = LessonService::cleanRequestData($request->all(), $excludeClean);


        $data = $request->only('chapter_id', 'title', 'content', 'duration', 'status');
        $data = [
            'chapter_id' => $cleanData['chapter_id'],
            'title' => $cleanData['title'],
            'content' => $request->input('content'),
            'duration' => $cleanData['duration'] ?? null,
            'status' => $cleanData['status'] ?? 1,
        ];
        $lastOrder = Lesson::where('chapter_id', $request->chapter_id)->max('order');
        $data['order'] = $lastOrder ? $lastOrder + 1 : 1;

        if ($request->video_url) {
            $videoUrl = $request->video_url;
            $parsedPath = parse_url($videoUrl, PHP_URL_PATH); 
            Log::info("Parsed video path: " . $parsedPath);
            $tmpPath = public_path($parsedPath); 
            Log::info("Temporary video path: " . $tmpPath);
            $videoPath = UploadHelper::getVideoUploadPath($course, $chapter);
            if (!file_exists($videoPath)) mkdir($videoPath, 0755, true);

            $fileName = basename($tmpPath);
            rename($tmpPath, $videoPath.'/'.$fileName); 
            $data['video'] = $fileName;
        }

        $lesson = Lesson::create($data);
        if ($lesson) {
            LogService::log(
                'CREATE',
                'Lesson',
                $lesson->id,
                'Create new lesson: ' . $lesson->title . ' COURSE ID:  ' . $lesson->chapter->course->id,
                $data
            );
            return redirect()->route('admin.lessons.index')->with('success', 'Lesson Created Successfully.');
        } else {
            if (isset($videoPath) && file_exists($videoPath . '/' . $fileName)) {
                unlink($videoPath . '/' . $fileName);
            }
            return back();
        }
    }

    public function edit(Lesson $lesson)
    {
        $courses = Course::orderBy('title', 'ASC')->get();
        $chaps = Chapter::orderBy('title', 'ASC')->get();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $videoPath = UploadHelper::getVideoUploadPath($lesson->chapter->course, $lesson->chapter);
        $publicUrl = url(str_replace(public_path(), '', $videoPath));
        
        $src = '';
        if (!empty($lesson->video)) {
            $src = $publicUrl . '/' . $lesson->video;
        }
        if ($user->hasRole('teacher')) {
            $courses = Course::where('user_id', '=', $user->id)->get();
            $chaps = Chapter::whereHas('course', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            })
                ->orderByDesc('id')
                ->get();        
            } 

        return view('admin.lessons.edit', compact('lesson', 'chaps', 'courses', 'src'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher') && $lesson->chapter->course->user_id !== Auth::id()) {
            return redirect()->route('admin.lessons.index')->with('no_access', 'You do not have permission to edit this lesson.');
        }
        $request->validate(FormValidationService::lessonRules($request));
        $dataToUpdate = LessonService::updateLesson($lesson, $request);
        LogService::log(
            'UPDATE',
            'Lesson',
            $lesson->id,
            'Updated lesson: ' . $lesson->title,
            $dataToUpdate
        );
        return redirect()->route('admin.lessons.index')->with('success', 'Lesson Updated Successfully.');;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher') && $lesson->chapter->course->user_id !== Auth::id()) {
            return redirect()->route('admin.lessons.index')->with('no_access', 'You do not have permission to edit this lesson.');
        }
        $chapter = $lesson->chapter;
        $chapterId = $chapter->id;
        $course = $chapter->course;
        $courseId = $course->id;
        $deleteOrder = $lesson->order;

        $data = [
            'title' => $lesson->title,
            'chapter_id' => $chapterId,
            'course_id' => $courseId,
            'video' => $lesson->video,
            'content' => $lesson->content,
            'status' => $lesson->status,
            'order' => $lesson->order,
        ];

        if ($lesson->video) {
            $videoPath = UploadHelper::getVideoUploadPath($course, $chapter) . '/' . $lesson->video;
            if (file_exists($videoPath)) {
                try {
                    unlink($videoPath);
                } catch (\Exception $e) {
                }
            }
        }
        $lesson->delete();
        Lesson::where('chapter_id', $chapterId)
            ->where('order', '>', $deleteOrder)
            ->orderBy('order')
            ->decrement('order');
        LogService::log(
            'DELETE',
            'Lesson',
            $lesson->id,
            'Deleted lesson: ' . $lesson->title,
            $data
        );

        return redirect()->back()->with('success', 'Lesson Deleted Successfully.');
    }

    public function uploadVideo(Request $request)
    {
        try {
            $url = LessonService::uploadVideo($request);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function __construct()
    {
        ob_start();
    }
}
