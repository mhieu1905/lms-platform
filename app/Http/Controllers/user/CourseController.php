<?php

namespace App\Http\Controllers\user;

use App\Helper\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Level;
use App\Services\CourseService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CourseController extends Controller
{
    /**
     * Display a paginated list of courses.
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Course::sortable(['id' => 'DESC'])->with(['user', 'level', 'category']);

        if ($user->hasRole('teacher')) {
            $query->where('user_id', $user->id);
        }

        $courses = $query->orderBy('id', 'DESC')->paginate(config('settings.pagination.per_page'));

        return response()
                ->view('admin.courses.index', compact('courses'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Show the form for creating a new courses.
     * 
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function create()
    {
        $categories = Category::all();
        $levels = Level::all();
        return view('admin.courses.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created course in the database.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function store(Request $request)
    {
        $validator = CourseService::validate($request);
        $validator->validate();
        $cleaned = $validator->getData();

        // Check duplicate information
        $duplicateCheck = Course::where([
            'title' => $cleaned['title'],
            'description' => $cleaned['description'],
            'regular_price' => $cleaned['regular_price'],
            'sale_price' => $cleaned['sale_price'],
            'duration' => $cleaned['duration'],
            'category_id' => $cleaned['category_id'],
            'level_id' => $cleaned['level_id'],
            'language' => $cleaned['language'],
            'user_id' => Auth::id(),
            'status' => 0,
        ])->exists();

        if ($duplicateCheck) {
            return back()->with('error', 'A course with the same information already exists.')->withInput();
        }

        $imagePath = null;

        // Handle temporary file
        if (!empty($cleaned['image_path'])) {
            $tempPath = str_replace('/storage/', '', $cleaned['image_path']);
            $disk = Storage::disk('public');

            if (!$disk->exists($tempPath)) {
                return back()->withErrors(['image' => 'File not found'])->withInput();
            }

            // Validate mime type & size
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $mime = $disk->mimeType($tempPath);
            $validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
            if (!in_array($mime, $validTypes)) {
                return back()->withErrors(['image' => 'File type is not allowed'])->withInput();
            }
            if ($disk->size($tempPath) > 2*1024*1024) {
                return back()->withErrors(['image' => 'File must be smaller than 2MB'])->withInput();
            }

            // Move file form temp folder to courses folder
            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $disk->makeDirectory('uploads/courses');
            $disk->move($tempPath, 'uploads/courses/' . $newName);

            $imagePath = 'uploads/courses/' . $newName;
        }

        $data = [
            'title' => $cleaned['title'],
            'description' => $cleaned['description'],
            'regular_price' => $cleaned['regular_price'],
            'sale_price' => $cleaned['sale_price'],
            'duration' => $cleaned['duration'],
            'category_id' => $cleaned['category_id'],
            'level_id' => $cleaned['level_id'],
            'language' => $cleaned['language'],
            'user_id' => Auth::id(),
            'status' => 0,
            'image' => $imagePath,
        ];

        $course = Course::create($data);

        if (!$course) {
            return back()->with('error', 'Failed to create course.')->withInput();
        }

        LogService::log(
            'CREATE',
            'Course',
            $course->id,
            'Created Course ID ' . $course->id,
            $data
        );

        return redirect()->route('admin.courses.index')->with('success', 'Course added successfully.');
    }

    /**
     * Show the form for editing a course.
     * 
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher') && $course->user_id != $user->id) {
            return redirect()->back()->with('no_access', 'You do not have permission to edit this course.');
        }
        $categories = Category::all();
        $levels = Level::all();

        return view('admin.courses.edit', compact('course', 'categories', 'levels'));
    }

    /**
     * Update an existing course in the database.
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function update(Request $request, $id)
    {
        $validator = CourseService::validate($request, true);
        $validator->validate();

        $course = Course::findOrFail($id);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher') && $course->user_id != $user->id) {
            return redirect()->back()->with('no_access', 'You do not have permission to edit this course.');
        }
        $dataToUpdate  = CourseService::updateCourse($course, $request);

        LogService::log(
            'UPDATE',
            'Course',
            $course->id,
            'Update Course ID ' . $course->id,
            $dataToUpdate
        );

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Delete a course from the database.
     * 
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Course not found or has already been deleted.');
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('teacher') && $course->user_id != $user->id) {
            return redirect()->back()->with('no_access', 'You do not have permission to delete this course.');
        }
        $data = [
            'title' => $course->title,
            'description' => $course->description,
            'regular_price' => $course->regular_price,
            'sale_price' => $course->sale_price,
            'duration' => $course->duration,
            'category_id' => $course->category_id,
            'level_id' => $course->level_id,
            'language' => $course->language,
            'user_id' => Auth::id(),
            'status' => $course->status,
            'image' => $course->image,
        ];
        if ($course->chapter()->exists()) {
            $page = (int) $request->get('page', 1);
            return redirect()->route('admin.courses.index', ['page' => $page])
                ->with('error', 'Course Cannot Delete Because It Has Related Lesson.');
        }

        $basePath = config('upload.video_base_path');
        $courseFolder = "{$course->id}--" . Str::slug($course->title);
        $coursePath = public_path("{$basePath}/{$courseFolder}");

        if (File::exists($coursePath)) {
            File::deleteDirectories($coursePath);
        }

        $course->delete();
        LogService::log(
            'DELETE',
            'Course',
            $course->id,
            'Deleted Course title ' . $course->title,
            $data
        );

        $perPage = config('settings.pagination.per_page');
        $page = (int) $request->get('page', 1);

        $query = Course::query();
        if ($user->hasRole('teacher')) {
            $query->where('user_id', $user->id);
        }

        $total = $query->count();
        $maxPage = (int) ceil($total / $perPage);
        if ($page > $maxPage && $maxPage > 1) {
            $page = $maxPage;
        }

        return redirect()->route('admin.courses.index', ['page' => $page])->with('success', 'Course deleted successfully.');
    }


    public function getCourseByChapter($chapter_id)
    {
        $chapter = Chapter::with('course')->find($chapter_id);

        if (!$chapter || !$chapter->course) {
            return response()->json(['Error' => 'Not Found'], 404);
        }

        return response()->json([
            'course_id' => $chapter->course->id,
            'course_title' => $chapter->course->title
        ]);
    }

    /**
     * Course approval
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @author Ho Luu Duc
     * Date: 10-09-2025
     */
    public function toggleStatus($id)
    {
        $course = Course::findOrFail($id);

        switch ($course->status) {
            case Course::STATUS_PENDING:
                $course->status = Course::STATUS_PUBLISHING;
                break;
            case Course::STATUS_PUBLISHING:
                $course->status = Course::STATUS_HIDDEN;
                break;
            case Course::STATUS_HIDDEN:
                $course->status = Course::STATUS_PUBLISHING;
                break;
            default:
                $course->status = Course::STATUS_PENDING;
        }

        $course->save();

        return response()->json([
            'status' => $course->status,
        ]);
    }
}
