<?php

namespace App\Services;

use App\Helper\UploadHelper;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CourseService
{
    /**
     * Clean up excess space
     * 
     * @param mixed $value
     * @return array|string|null
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    public static function cleanRequestData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = self::cleanInput($value);
            }
        }

        return $data;
    }

    /**
     * Validate course request data for creation or update.
     * @param \Illuminate\Http\Request $request
     * @param mixed $isUpdate
     * @return \Illuminate\Validation\Validator
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function validate(Request $request, $isUpdate = false)
    {
        $cleaned = self::cleanRequestData($request->all());

        $rules = [
            'title' => 'required|string|max:70',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'language' => 'required|string|max:50',
            'duration' => 'required|integer|min:1|lt:121',
            'regular_price' => 'required|numeric|min:0|lt:1000000|regex:/^\d+(\.\d{1,2})?$/',
            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
                function ($attribute, $value, $fail) use ($cleaned) {
                    // Check if regular_price exists in the data
                    if (array_key_exists('regular_price', $cleaned)) {
                        $regularPrice = $cleaned['regular_price'];

                        // Check if sale_price has a value and is greater than or equal to regular_price
                        if ($value !== null && $value >= $regularPrice) {
                            $fail('The sale price must be less than the regular price.');
                        }
                    }
                },
            ],
        ];

        if ($isUpdate) {
            $rules['image_path'] = 'nullable|string';
        } else {
            $rules['image_path'] = 'required|string';
        }

        if (!$isUpdate && !$request->filled('image_path')) {
            $validator = Validator::make([], []);
            $validator->errors()->add('image', 'Please upload an image.');
            throw new ValidationException($validator);
        }

        return Validator::make($cleaned, $rules);
    }

    /**
     * Update a course's data with new request values.
     * 
     * @param \App\Models\Course $course
     * @param \Illuminate\Http\Request $request
     * @return array<array|mixed|string|null>|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function updateCourse(Course $course, Request $request)
    {
        $cleaned = self::cleanRequestData($request->all());

        $fieldToCheck = [
            'title',
            'description',
            'regular_price',
            'sale_price',
            'duration',
            'category_id',
            'level_id',
            'language',
        ];

        $dataToUpdate = [];

        $oldCourseId = $course->id;
        $oldCourseTitle = $course->title;

        foreach ($fieldToCheck as $field) {
            $newValue = $cleaned[$field] ?? null;

            if ((string) $newValue != (string) $course->$field) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if (!empty($cleaned['image_path'])) {
            $tempPath = str_replace('/storage/', '', $cleaned['image_path']);
            $disk = Storage::disk('public');

            if ($course->image && $disk->exists($course->image)) {
                $disk->delete($course->image);
            }

            if (!$disk->exists($tempPath)) {
                return back()->withErrors(['image' => 'File not found'])->withInput();
            }

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $mime = $disk->mimeType($tempPath);
            $validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
            if(!in_array($mime, $validTypes)) {
                return back()->withErrors(['image' => 'File type is not allowed'])->withInput();
            }

            if (!$disk->exists('uploads/courses')) {
                $disk->makeDirectory('uploads/courses');
            }

            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $newPath = 'uploads/courses/' . $newName;

            Storage::disk('public')->move($tempPath, $newPath);

            $dataToUpdate['image'] = $newPath;
        }

        if (!empty($dataToUpdate)) {
            $course->update($dataToUpdate);
            $course->refresh();
            if (isset($dataToUpdate['title'])) {
                UploadHelper::renameCourseFolder($oldCourseId, $oldCourseTitle, $course);
            }
        }

        return $dataToUpdate;
    }
    
    public static function showCourses()
    {
        $courses = Course::withCount('lessons', 'enrolledUsers')
                ->where('status', '=', 1)
                ->orderBy('id', 'asc')
                ->paginate(config('settings.courses_home.per_page'));
        return $courses;
    }

    // Show courses on the homepage
    public static function showCoursesHome($request = null) {
        $limit = config('settings.all_courses.per_page');

        $query = Course::where('status', 1)
            ->withCount('lessons', 'enrolledUsers')
            ->orderByDesc('id');
        
            if ($request) {
                if ($request->filled('category')) {
                    $query->where('category_id', $request->category);
                }
                if ($request->filled('level')) {
                    $query->where('level_id', $request->level);
                }
            } 

        return $query->paginate($limit);
    }
}
