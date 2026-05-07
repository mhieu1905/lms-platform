<?php

namespace App\Http\View\Composers;

use App\Models\Course;
use Illuminate\View\View;

/**
 * Summary of PopularCourse
 */
class PopularCourse
{
    /**
     * Share popular courses data with multiple views.
     * 
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        $viewName = $view->getName();
        $limits = config('settings.popular_courses.limits');
        $limit = $limits[$viewName] ?? config('settings.popular_courses.default');
        $popularCourses = Course::where('status', 1)
            ->withCount('lessons', 'enrolledUsers')
            ->orderByDesc('enrolled_users_count')
            ->limit($limit)
            ->get();

        $view->with(compact(
            'popularCourses'
        ));
    }
}
