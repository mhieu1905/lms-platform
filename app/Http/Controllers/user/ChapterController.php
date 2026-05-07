<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Footer;
use App\Services\ChapterService;
use App\Services\FormValidationService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Chapter::sortable(['id' => 'DESC'])->paginate(config('settings.pagination.per_page'));
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $data = Chapter::whereHas('course', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with('course')
                ->sortable(['id' => 'DESC'])
                ->paginate(config('settings.pagination.per_page'));
        }
        return view('admin.chapters.index', compact('data'));
    }

    /**
     *  the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Course::with(['user', 'level', 'category']);

        if ($user->hasRole('teacher')) {
            $query->where('user_id', $user->id);
        }

        $courses = $query->get();


        return view('admin.chapters.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(FormValidationService::chapterRules($request));

        $data = [
            'title' => $request->title,
            'course_id' => $request->course_id
        ];


        $chapter = Chapter::create($data);

        if ($chapter) {
            LogService::log(
                'CREATE',
                'Chapter',
                $chapter->id,
                'Created Chapter title ' . $chapter->title,
                $data
            );
        }
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter Created Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        $courses = Course::orderBy('title', 'ASC')->get();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $courses = Course::where('user_id', '=', $user->id)->get();
        }
        return view('admin.chapters.edit', compact('chapter',  'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        $request->validate(FormValidationService::chapterRules($request));
        $dataToUpdate = ChapterService::updateChapter($chapter, $request);
        LogService::log(
            'UPDATE',
            'Chapter',
            $chapter->id,
            'Updated Chapter: ' . $chapter->title,
            $dataToUpdate
        );
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        if ($chapter->lessons()->exists()) {
            return redirect()->route('admin.chapters.index')->with('error', 'Can Not Delete This Chapter Because It Has Related Lesson.');
        }
        $data = [
            'title' => $chapter->title,
            'course_id' => $chapter->course_id
        ];
        $basePath = config('upload.video_base_path');
        $chapterFolder = "{$chapter->title}--" . Str::slug($chapter->title);
        $chapterPath = public_path("{$basePath}/{$chapterFolder}");

        if (File::exists($chapterPath)) {
            File::deleteDirectories($chapterPath);
        }

        $chapter->delete();
        LogService::log(
            'DELETE',
            'Chapter',
            $chapter->id,
            'Deleted Chapter ' . $chapter->title,
            $data
        );
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter Deleted uccessfully.');
    }

    /*Retrive Chapter by Course*/
    public function getChapters($course_id)
    {
        $chapters = Chapter::where('course_id', $course_id)->orderBy('title')->get();
        return response()->json($chapters);
    }

    /*Fill course by Chapter */
    public function getAllChapters()
    {
        $chapters = Chapter::orderBy('title')->get();
        return response()->json($chapters);
    }
}
