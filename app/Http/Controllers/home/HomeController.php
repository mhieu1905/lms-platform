<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Footer;
use App\Models\FooterSection;
use App\Models\HomePage\Slider;
use App\Models\Level;
use App\Models\News;
use App\Services\CourseService;
use App\Services\EventService;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the homepage with sliders, popular courses, and upcoming events.
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 19-08-2025
     */
    public function showHome(Request $request)
    {
        $sliders = Slider::orderBy('id', 'DESC')->get();

        $news = News::where('status', config('settings.status.public'))
            ->orderByDesc('id')
            ->get();
        $events = EventService::showEventUpcoming();
        $courses = CourseService::showCoursesHome($request);
        $categories = Category::all();
        $levels = Level::all();

        $activeFilters = [];

        if ($request->filled('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $activeFilters['category'] = $category->name;
            }
        }

        if ($request->filled('level')) {
            $level = Level::find($request->level);
            if ($level) {
                $activeFilters['level'] = $level->name;
            }
        }


        return response()
                ->view('home.index', compact('sliders', 'events', 'news', 'courses', 'categories', 'levels', 'activeFilters'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Display the events page with categorized event lists.
     *
     * @return \Illuminate\View\View
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function showEvents()
    {
        $eventsHappening = EventService::showEventHappening();
        $eventsUpcoming = EventService::showEventUpcoming();
        $eventExpired = EventService::showEventExpired();

        return view('home.events', compact('eventsHappening', 'eventsUpcoming', 'eventExpired'));
    }

    /**
     * Show the details of an event by its ID.
     * @param mixed $id
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function showEventsDetails($id)
    {
        $event = EventService::showEventsDetails($id);
        $hasBooked = false;

        if (Auth::check()) {
            $user = Auth::user();
            $hasBooked = TicketService::hasUserBookedTicket($user, $event);
        }

        $remainingSlots = $event->total_slots - $event->booked_slots;

        $canBook = $event->finish_time > now();

        return response()
                ->view('home.events-details', compact('event', 'hasBooked', 'remainingSlots', 'canBook'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Show all courses on course page on hơmepage
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 29-09-2025
     */
    public function showCourses(Request $request)
    {
        $categories = Category::all();
        $levels = Level::all();
        $courses = CourseService::showCoursesHome($request);

        $activeFilters = [];

        if ($request->filled('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $activeFilters['category'] = $category->name;
            }
        }

        if ($request->filled('level')) {
            $level = Level::find($request->level);
            if ($level) {
                $activeFilters['level'] = $level->name;
            }
        }

        return view('home.courses', compact('courses', 'categories', 'levels', 'activeFilters'));
    }

    /**
     * Handle loadmore course when scroll
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @author Ho Luu Duc
     * Date: 25-09-2025
     */
    public function loadMore(Request $request)
    {
        $limit = config('settings.all_courses.per_page');
        $page  = $request->input('page', 1);

        $query = Course::where('status', 1);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('level')) {
            $query->where('level_id', $request->level);
        }

        $courses = $query->withCount('lessons', 'enrolledUsers')
        ->orderByDesc('id')
        ->paginate($limit, ['*'], 'page', $page);

        $html = view('home.partials.course-cards', compact('courses'))->render();

        return response()->json([
            'html' => $html,
            'next_page' => $courses->hasMorePages() ? $page + 1 : null,
        ]);
    }
}
