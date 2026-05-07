<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class CvAnalysisController extends Controller
{
    /**
     * Search courses based on keywords in title, level name, or category name.
     * Author: Minh Hieu
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCourses(Request $request)
    {
        $input = $request->input('suggestion_skills');
        Log::info("Search input: " . json_encode($input));

        $query = Course::select('id', 'title', 'image', 'regular_price', 'sale_price' ,'category_id', 'level_id')
            ->with([
                'level:id,name',
                'category:id,name'
            ])
            ->where('status', config('settings.status.public'));

        $results = collect(); 

        if (is_array($input)) {
            foreach ($input as $item) {
                $category = $item['categories'] ?? null;
                $level = $item['level'] ?? null;
                $keywords = $item['keywords'] ?? [];

                $keywords = array_filter($keywords, fn($k) => $k !== null && trim($k) !== '');

                if (!empty($keywords)) {
                    $subQuery = clone $query;
                    $subQuery->where(function ($q) use ($keywords) {
                        foreach ($keywords as $keyword) {
                            $escapedKeyword = str_replace(['%', '_', '\\'], ['\%', '\_', '\\\\'], trim($keyword));
                            Log::info("Searching by keyword: " . $escapedKeyword);
                            $q->orWhere('title', 'LIKE', "%{$escapedKeyword}%");
                        }
                    });
                    $found = $subQuery->get();
                    $results = $results->merge($found);

                    if ($found->isNotEmpty()) {
                        $results = $results->merge($found);
                        continue;
                    }
                }

                $subQuery = clone $query;
                $subQuery->whereHas('category', function ($sub) use ($category) {
                    $sub->where('name', 'LIKE', "%{$category}%");
                });

                if (!empty($level)) {
                    $subQuery->whereHas('level', function ($sub) use ($level) {
                        $sub->where('name', 'LIKE', "%{$level}%");
                    });
                }

                $found = $subQuery->get();

                if ($found->isEmpty() && !empty($category)) {
                    Log::info("No match for cate+level, fallback by cate only: {$category}");
                    $fallbackQuery = clone $query;
                    $fallbackQuery->whereHas('category', function ($sub) use ($category) {
                        $sub->where('name', 'LIKE', "%{$category}%");
                    });
                    $found = $fallbackQuery->get();
                }

                $results = $results->merge($found);
            }
        }

        $uniqueResults = $results->unique('id')->values();
        $uniqueResults = $uniqueResults->map(function ($course) {
            $course->makeHidden(['category_id', 'level_id']);
            $course->image = url("storage/{$course->image}");
            $course->link = url("courses/{$course->id}");
            return $course;
        });

        return response()->json($uniqueResults);


        foreach ($uniqueResults as $course) {
            Log::info("Matched course: ID={$course->id}, title={$course->title}, level={$course->level->name}, category={$course->category->name}");
        }

        return response()->json($uniqueResults);
    }
}
