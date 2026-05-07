<?php

namespace App\Services;

use App\Models\Chapter;
use Illuminate\Http\Request;
use App\Helper\UploadHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChapterService
{
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    public static function updateChapter(Chapter $chapter, Request $request)
    {
        $fieldToCheck = [
            'title',
            'course_id',
        ];

        $dataToUpdate = [];

        foreach ($fieldToCheck as $field) {
            $newValue = $request->input($field);

            if (is_string($newValue)) {
                $newValue = self::cleanInput($newValue);
            }

            if ($newValue !== $chapter->$field) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if (!empty($dataToUpdate)) {
            $oldCourseId = $chapter->course->id;
            $oldCourseTitle = $chapter->course->title;
            $oldChapterId = $chapter->id;
            $oldChapterTitle = $chapter->title;

            $chapter->update($dataToUpdate);
            $chapter->refresh();
            $course = $chapter->course;

            UpLoadHelper::renameChapterFolder($oldCourseId, $oldCourseTitle, $oldChapterId, $oldChapterTitle, $course, $chapter);

            $basePath = config('upload.video_base_path');
            $oldCoursePath = public_path("{$basePath}/{$oldCourseId}--" . Str::slug($oldCourseTitle));

            if (
                File::exists($oldCoursePath)
                && count(File::directories($oldCoursePath)) === 0
                && count(File::files($oldCoursePath)) === 0
            ) {
                File::deleteDirectory($oldCoursePath);
            }
        }

        return $dataToUpdate;
    }
}
