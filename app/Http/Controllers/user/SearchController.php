<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Event;
use App\Models\HomePage\Slider;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Major;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request, $model)
    {
        $configs = [
            'levels' => [
                'class' => Level::class,
                'columns' => ['name'],
                'view' => 'admin.levels.index',
                'var' => 'data',
            ],
            'categories' => [
                'class' => Category::class,
                'columns' => ['name'],
                'view' => 'admin.categories.index',
                'var' => 'data',
            ],
            'courses' => [
                'class' => Course::class,
                'columns' => ['title'],
                'relations' => ['category' => 'name'],
                'with' => ['category'],
                'view' => 'admin.courses.index',
                'var' => 'courses',
                'owner_relation' => 'user_id',
            ],
            'chapters' => [
                'class' => Chapter::class,
                'columns' => ['title'],
                'relations' => ['course' => 'title'],
                'with' => ['course'],
                'view' => 'admin.chapters.index',
                'var' => 'data',
                'owner_relation' => 'course.user_id',
            ],
            'lessons' => [
                'class' => Lesson::class,
                'columns' => ['title', 'status'],
                'relations' => [
                    'chapter' => 'title',
                    'chapter.course' => 'title',
                ],
                'with' => ['chapter.course'],
                'view' => 'admin.lessons.index',
                'var' => 'data',
                'owner_relation' => 'chapter.course.user_id',
            ],
            'events' => [
                'class' => Event::class,
                'columns' => ['title', 'status'],
                'view' => 'admin.events.index',
                'var' => 'events',
            ],
            'sliders' => [
                'class' => Slider::class,
                'columns' => ['title', 'status'],
                'view' => 'cms.home-page.slider',
                'var' => 'sliders',
            ],
            'news' => [
                'class' => News::class,
                'columns' => ['title', 'status'],
                'relations' => ['category' => 'name'],
                'with' => ['category'],
                'view' => 'admin.news.index',
                'var' => 'news'
            ],
            'majors' => [
                'class' => Major::class,
                'columns' => ['name'],
                'view' => 'admin.majors.index',
                'var' => 'majors',
            ],
            'applications' => [
                'class' => User::class,
                'columns' => ['name', 'email'],
                'with' => ['majors'],
                'view' => 'admin.users.teacher_applications.index',
                'var' => 'applications',
            ],
            'admins' => [
                'class' => User::class,
                'columns' => ['name', 'email'],
                'view' => 'admin.users.admins.index',
                'var' => 'admins',
            ],
            'students' => [
                'class' => User::class,
                'columns' => ['name', 'email'],
                'view' => 'admin.users.students.index',
                'var' => 'students',
            ],
            'teachers' => [
                'class' => User::class,
                'columns' => ['name', 'email'],
                'view' => 'admin.users.teachers.index',
                'var' => 'teachers',
            ],
        ];

        if (!isset($configs[$model])) {
            abort(404);
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home.index')->with('error', 'You need to login to access.');
        }

        $rolePermissions = [
            'admin'   => [
                'courses',
                'chapters',
                'lessons',
                'levels',
                'categories',
                'news',
                'events',
                'sliders',
                'majors',
                'applications',
                'admins',
                'students',
                'teachers',
            ],

            'teacher' => [
                'courses',
                'chapters',
                'lessons',
            ],
        ];

        if ($user->hasRole('admin')) {
            $allowed = $rolePermissions['admin'];
        } elseif ($user->hasRole('teacher')) {
            $allowed = $rolePermissions['teacher'];
        } else {
            $allowed = [];
        }

        if (!in_array($model, $allowed)) {
            return redirect()->route('home.index')->with('error', 'You dont have permission to access this page.');
        }

        $config = $configs[$model];
        $query = $config['class']::sortable()->with($config['with'] ?? []);

        if ($model === 'applications') {
            $query->where('status', 1);
        }

        if ($model === 'admins') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            });
        }

        if ($model === 'teachers') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'teacher');
            });
        }

        if ($model === 'students') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            });
        }

        if ($user && $user->hasRole('teacher') && !empty($config['owner_relation'])) {
            $this->applyOwnerFilter($query, $config['owner_relation']);
        }

        $search = $request->input('search');
        if ($search !== null && $search !== '') {
            $escapedSearch = str_replace(['%', '_', '\\'], ['\%', '\_', '\\\\'], $search);
            $isNumericSearch = is_numeric($search);
            $query->where(function ($q) use ($config, $escapedSearch, $isNumericSearch) {
                foreach ($config['columns'] as $column) {
                    if ($column === 'status' && $isNumericSearch) {
                        continue;
                    }
                    $q->orWhere($column, 'LIKE', "%{$escapedSearch}%");
                }

                if (isset($config['relations'])) {
                    foreach ($config['relations'] as $relation => $column) {
                        $q->orWhereHas($relation, function ($subQuery) use ($column, $escapedSearch) {
                            $subQuery->where($column, 'LIKE', "%{$escapedSearch}%");
                        });
                    }
                }

                Log::info('Inside closure - isNumericSearch check:', [
            'isNumericSearch' => $isNumericSearch,
            'willApplyStatusMapping' => !$isNumericSearch
        ]);
        
        if (!$isNumericSearch) {
            Log::info('Applying status mapping for non-numeric search');
            $statusMap = [
                'trial'  => 0,
                'purchase' => 1,
                'hidden' => 0,
                'public' => 1
            ];

            $lower = strtolower($escapedSearch);
            if (isset($statusMap[$lower])) {
                Log::info('Status mapping matched:', [
                    'searchTerm' => $lower,
                    'statusValue' => $statusMap[$lower]
                ]);
                $q->orWhere('status', $statusMap[$lower]);
            } else {
                Log::info('No status mapping found for term: ' . $lower);
            }
        } else {
            Log::info('Skipping status mapping - search is numeric');
        }

        Log::info('Search value final:', [
            'escapedSearch' => $escapedSearch,
            'is_numeric' => $isNumericSearch,
        ]);
            });
        }

        $data = $query->paginate(config('settings.pagination.per_page'))->appends($request->all());



        return view($config['view'], [
            $config['var'] => $data
        ]);
    }

    protected function applyOwnerFilter($query, $ownerRelation)
    {
        $relations = explode('.', $ownerRelation);
        $last = array_pop($relations);

        if (empty($relations)) {
            $query->where($last, Auth::id());
        } else {
            $this->applyOwnerRelation($query, $relations, $last);
        }
    }

    protected function applyOwnerRelation($query, $relations, $last)
    {
        $relation = array_shift($relations);

        $query->whereHas($relation, function ($sub) use ($relations, $last) {
            if (empty($relations)) {
                $sub->where($last, Auth::id());
            } else {
                $this->applyOwnerRelation($sub, $relations, $last);
            }
        });
    }
}
