<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UploadHelper
{
    public static function getVideoUploadPath($course, $chapter, $fileName = '')
    {
        $basePath = config('upload.video_base_path');
        $courseFolder = "{$course->id}--" . Str::slug($course->title);
        $chapterFolder = "{$chapter->id}--" . Str::slug($chapter->title);
        return public_path("{$basePath}/{$courseFolder}/{$chapterFolder}/{$fileName}");
    }

    public static function renameCourseFolder($oldCourseId, $oldCourseTitle, $course)
    {
        $basePath = config('upload.video_base_path');

        $oldCourseFolder = "{$oldCourseId}--" . Str::slug($oldCourseTitle);
        $newCourseFolder = "{$course->id}--" . Str::slug($course->title);

        $oldCoursePath = public_path("{$basePath}/{$oldCourseFolder}");
        $newCoursePath = public_path("{$basePath}/{$newCourseFolder}");

        if (File::exists($oldCoursePath) && $oldCourseFolder !== $newCourseFolder) {
            rename($oldCoursePath, $newCoursePath);

            if (File::exists($oldCoursePath) && count(File::directories($oldCoursePath)) === 0 && count(File::files($oldCoursePath)) === 0) {
                File::deleteDirectory($oldCoursePath);
            }

            return true;
        }
        return false;
    }
    public static function renameChapterFolder($oldCourseId, $oldCourseTitle, $oldChapterId, $oldChapterTitle, $course, $chapter)
    {
        $basePath = config('upload.video_base_path');

        $oldCourseFolder = "{$oldCourseId}--" . Str::slug($oldCourseTitle);
        $oldChapterFolder = "{$oldChapterId}--" . Str::slug($oldChapterTitle);
        $oldPath = public_path("{$basePath}/{$oldCourseFolder}/{$oldChapterFolder}");

        $newCourseFolder = "{$course->id}--" . Str::slug($course->title);
        $newChapterFolder = "{$chapter->id}--" . Str::slug($chapter->title);
        $newPath = public_path("{$basePath}/{$newCourseFolder}/{$newChapterFolder}");

        if (!File::exists(dirname($newPath))) {
            File::makeDirectory(dirname($newPath), 0755, true);
        }

        if (File::exists($oldPath)) {
            rename($oldPath, $newPath);
        } else {
            File::makeDirectory($newPath, 0755, true);
        }
        return true;
    }
    public static function renameLessonFolder($oldCourseId, $oldCourseTitle, $oldChapterId, $oldChapterTitle, $lesson, $newCourse, $newChapter)
    {
        $basePath = config('upload.video_base_path');

        $oldCourseFolder = "{$oldCourseId}--" . Str::slug($oldCourseTitle);
        $oldChapterFolder = "{$oldChapterId}--" . Str::slug($oldChapterTitle);
        $oldPath = public_path("{$basePath}/{$oldCourseFolder}/{$oldChapterFolder}/{$lesson->video}");

        $newCourseFolder = "{$newCourse->id}--" . Str::slug($newCourse->title);
        $newChapterFolder = "{$newChapter->id}--" . Str::slug($newChapter->title);
        $newPath = public_path("{$basePath}/{$newCourseFolder}/{$newChapterFolder}");

        if (!File::exists($newPath)) {
            File::makeDirectory($newPath, 0755, true);
        }

        $newVideoPath = $newPath . '/' . $lesson->video;

        if (File::exists($oldPath) && $oldPath !== $newVideoPath) {
            File::move($oldPath, $newVideoPath);
            $oldChapterFolderPath = dirname($oldPath);
            if (count(File::files($oldChapterFolderPath)) === 0) {
                File::deleteDirectory($oldChapterFolderPath);
            }
            return true;
        }
        return false;
    }
}
