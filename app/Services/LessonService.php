<?php

namespace App\Services;

use App\Helper\UploadHelper;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LessonService
{
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    public static function cleanRequestData(array $data, array $excludeKeys)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $excludeKeys, true)) {
                continue;
            }
            if (is_string($value)) {
                $data[$key] = self::cleanInput($value);
            }
        }
        return $data;
    }
    public static function updateLesson(Lesson $lesson, Request $request)
    {
        $excludeClean = ['content', 'video'];

        $cleanData = self::cleanRequestData($request->all(), $excludeClean);

        $fieldToCheck = [
            'title',
            'chapter_id',
            'video',
            'content',
            'duration',
            'status',
        ];

        $oldCourseId = $lesson->chapter->course->id;
        $oldCourseTitle = $lesson->chapter->course->title;
        $oldChapterId = $lesson->chapter->id;
        $oldChapterTitle = $lesson->chapter->title;

        $chapter = !empty($cleanData['chapter_id'])
            ? Chapter::findOrFail($cleanData['chapter_id'])
            : $lesson->chapter;

        $course = $chapter->course;

        $dataToUpdate = [];

        foreach ($fieldToCheck as $field) {
            if ($field === 'video' && empty($cleanData[$field])) {
                continue;
            }

            $newValue = $cleanData[$field] ?? null;
            $currentValue = $lesson->$field;

            if ($newValue !== $currentValue) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if ($request->video_url) {
            $oldPath = UploadHelper::getVideoUploadPath($course, $chapter, $lesson->video);
            if (file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $videoUrl = $request->video_url;
            $parsedPath = parse_url($videoUrl, PHP_URL_PATH); 
            Log::info("Parsed video path: " . $parsedPath);
            $tmpPath = public_path($parsedPath); 
            Log::info("Temporary video path: " . $tmpPath);
            $videoPath = UploadHelper::getVideoUploadPath($course, $chapter);
            if (!file_exists($videoPath)) mkdir($videoPath, 0755, true);

            $fileName = basename($tmpPath);
            rename($tmpPath, $videoPath . '/' . $fileName); 
            $dataToUpdate['video'] = $fileName;
        }

        if (!empty($dataToUpdate)) {
            if (isset($dataToUpdate['chapter_id'])) {
                $newChapterId = $dataToUpdate['chapter_id'];

                if ($newChapterId != $lesson->chapter_id) {
                    $lastOrder = Lesson::where('chapter_id', $newChapterId)->max('order');
                    $dataToUpdate['order'] = $lastOrder ? $lastOrder + 1 : 1;
                }

                $chapter = Chapter::findOrFail($newChapterId);
                $course = $chapter->course;

                $dataToUpdate['course_id'] = $chapter->course_id;
            } else {
                $dataToUpdate['course_id'] = $lesson->course_id;
            }

            $lesson->update($dataToUpdate);
            $lesson->refresh();
            $chapter = $lesson->chapter;
            $course = $chapter->course;
            if (($oldChapterTitle !== $chapter->title) || ($oldCourseTitle !== $course->title)) {
                UploadHelper::renameLessonFolder($oldCourseId, $oldCourseTitle, $oldChapterId, $oldChapterTitle, $lesson, $course, $chapter);
            }
        }

        return $dataToUpdate;
    }

    public static function uploadVideo(Request $request)
    {
        if (!$request->hasFile('video')) {
            throw new \Exception("No video uploaded");
        }

        $file = $request->file('video');

        $allowed = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowed)) {
            throw new \Exception("Invalid video format");
        }

        if ($file->getSize() > 51200 * 1024) {
            throw new \Exception("Video size exceeds limit");
        }

        $path = $file->store('tmp/videos', 'public');

        return asset('storage/' . $path);
    }
}
