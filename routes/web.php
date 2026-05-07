<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\StudentController;
use App\Http\Controllers\admin\TeacherApplicationController;
use App\Http\Controllers\admin\TeacherController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\ForgotPasswordController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\RegisterTeacherController;
use App\Http\Controllers\auth\ResetPasswordController;
use App\Http\Controllers\cms\FooterController;
use App\Http\Controllers\user\MajorController;
use App\Http\Controllers\user\NewsController;
use App\Http\Controllers\home\CourseController as HomeCourseController;
use App\Http\Controllers\cms\home_page\SliderController;
use App\Http\Controllers\home\HomeController;
use App\Http\Controllers\home\LessonController as HomeLessonController;
use App\Http\Controllers\home\ProfileController;
use App\Http\Controllers\home\PaymentController;
use App\Http\Controllers\user\SearchController;
use App\Http\Controllers\user\LessonController;
use App\Http\Controllers\user\CourseController;
use App\Http\Controllers\user\CategoryController;
use App\Http\Controllers\user\ChapterController;
use App\Http\Controllers\user\EventController;
use App\Http\Controllers\user\LevelController;
use App\Http\Controllers\home\OrderController;
use App\Http\Controllers\user\DashboardController;
use App\Http\Controllers\user\TemporaryUploadController;
use App\Http\Controllers\user\TicketController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    
});
Route::get('/', [HomeController::class, 'showHome'])->name('home.index');
Route::get('/admin/{model}/search', [SearchController::class, 'index'])->name('search');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// Profile on the home page
Route::get('/profile/my-courses', [ProfileController::class, 'index'])->name('profile.index')->middleware('check.permission:view_profile');
Route::get('/profile/details', [ProfileController::class, 'showProfileDetails'])->name('profile.details')->middleware('check.permission:view_profile');
Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update')->middleware('check.permission:edit_profile');
Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('change.password.form')->middleware('check.permission:change_password');
Route::patch('/profile/change-password', [ProfileController::class, 'changePassword'])->name('change.password')->middleware('check.permission:change_password');

// ORDER
Route::get('/orders/create/{type}/{id}', [OrderController::class, 'create'])->name('orders.create')->middleware('check.permission:orders.create');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store')->middleware('check.permission:orders.store');

// PAYMENT
Route::get('orders/{id}/payments', [PaymentController::class, 'show'])->name('payments.show')->middleware('check.permission:payments.show');
Route::post('/payments/{payment_id}/check-status', [PaymentController::class, 'checkStatus'])->name('payments.check_status')->middleware('check.permission:payments.check_status');
Route::post('/payments/{id}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.update_status')->middleware('check.permission:payments.update_status');
Route::post('/courses/{course}/enroll', [HomeCourseController::class, 'enrollFree'])->name('courses.enroll-free')->middleware('check.permission:enroll-free');
Route::post('orders/paypal', [OrderController::class, 'orderPayPal'])->name('orders.orderPayPal')->middleware('check.permission:orders.orderPayPal');


// Events on the home page
Route::get('/events', [HomeController::class, 'showEvents'])->name('events.index');
Route::get('/events-details/{id}', [HomeController::class, 'showEventsDetails'])->name('events.show');
Route::post('/events/{id}/buy-ticket', [TicketController::class, 'buy'])->name('events.buy')->middleware('check.permission:buy_ticket_events');

// Courses on the home page
Route::get('/courses', [HomeController::class, 'showCourses'])->name('courses.index');
Route::post('/courses/{course}/buy', [HomeCourseController::class, 'buy'])->name('courses.buy')->middleware('check.permission:buy_course');
Route::get('/courses/load-more', [HomeController::class, 'loadMore'])->name('courses.loadMore');
Route::get('/courses/{id}', [HomeCourseController::class, 'show'])->name('courses.show');

Route::post('/courses/{course}/finish', [HomeCourseController::class, 'finish'])->name('courses.finish')->middleware('check.permission:finish_course');
Route::delete('/courses/{course}/retake', [HomeCourseController::class, 'retake'])->name('courses.retake')->middleware('check.permission:retake_course');

Route::post('/courses/{course}/lessons/{lesson}/complete', [HomeLessonController::class, 'complete'])->name('lessons.complete')->middleware('check.permission:complete_lesson');
Route::get('/courses/{course}/lessons/{lesson}', [HomeLessonController::class, 'show'])->name('lessons.show');

// News on the home page
Route::get('/news', [NewsController::class, 'showHome'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index')->middleware('check.permission:view_courses');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create')->middleware('check.permission:create_courses');
    Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store')->middleware('check.permission:create_courses');
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit')->middleware('check.permission:edit_courses');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update')->middleware('check.permission:edit_courses');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy')->middleware('check.permission:delete_courses');
    Route::patch('/courses/{id}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggleStatus')->middleware('check.permission:toggle_courses');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('check.permission:view_dashboard');
      
    // Levels routes
    Route::get('/levels', [LevelController::class, 'index'])->name('levels.index')->middleware('check.permission:manage_levels');
    Route::get('/levels/create', [LevelController::class, 'create'])->name('levels.create')->middleware('check.permission:manage_levels');
    Route::post('/levels', [LevelController::class, 'store'])->name('levels.store')->middleware(['check.permission:manage_levels', 'prevent.double.submit']);
    Route::get('/levels/{level}', [LevelController::class, 'show'])->name('levels.show')->middleware('check.permission:manage_levels');
    Route::get('/levels/{level}/edit', [LevelController::class, 'edit'])->name('levels.edit')->middleware('check.permission:manage_levels');
    Route::put('/levels/{level}', [LevelController::class, 'update'])->name('levels.update')->middleware(['check.permission:manage_levels', 'prevent.double.submit']);
    Route::delete('/levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy')->middleware('check.permission:manage_levels');
    
    // Categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('check.permission:manage_categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('check.permission:manage_categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware(['check.permission:manage_categories', 'prevent.double.submit']);
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('check.permission:manage_categories');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('check.permission:manage_categories');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware(['check.permission:manage_categories', 'prevent.double.submit']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('check.permission:manage_categories');
    
    // Chapters routes  
    Route::get('/chapters', [ChapterController::class, 'index'])->name('chapters.index')->middleware('check.permission:manage_chapters');
    Route::get('/chapters/create', [ChapterController::class, 'create'])->name('chapters.create')->middleware('check.permission:manage_chapters');
    Route::post('/chapters', [ChapterController::class, 'store'])->name('chapters.store')->middleware(['check.permission:manage_chapters', 'prevent.double.submit']);
    Route::get('/chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show')->middleware('check.permission:manage_chapters');
    Route::get('/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit')->middleware('check.permission:manage_chapters');
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update')->middleware(['check.permission:manage_chapters', 'prevent.double.submit']);
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy')->middleware('check.permission:manage_chapters');
    
    // Lessons routes
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index')->middleware('check.permission:manage_lesson');
    Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create')->middleware('check.permission:manage_lesson');
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store')->middleware(['check.permission:manage_lesson', 'prevent.double.submit']);
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show')->middleware('check.permission:manage_lesson');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit')->middleware('check.permission:manage_lesson');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update')->middleware(['check.permission:manage_lesson', 'prevent.double.submit']);
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy')->middleware('check.permission:manage_lesson');
    Route::get('get-all-chapters', [ChapterController::class, 'getAllChapters'])->middleware('check.permission:manage_lesson');
    Route::get('get-chapters/{course_id}', [ChapterController::class, 'getChapters'])->middleware('check.permission:manage_lesson');
    Route::get('get-course-by-chapter/{chapter_id}', [CourseController::class, 'getCourseByChapter'])->middleware('check.permission:manage_lesson');
    Route::post('admin/lessons/upload-video', [LessonController::class, 'uploadVideo'])->name('lessons.upload_video')->middleware('check.permission:manage_lesson');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create')->middleware('check.permission:news_management');
    Route::post('/news/store', [NewsController::class, 'store'])->name('news.store')->middleware(['check.permission:news_management', 'prevent.double.submit']);
    Route::get('/news', [NewsController::class, 'index'])->name('news.index')->middleware('check.permission:news_management');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit')->middleware('check.permission:news_management');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('news.update')->middleware('check.permission:news_management');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('news.destroy')->middleware('check.permission:news_management');
    Route::patch('/news/{id}/toggle-status', [NewsController::class, 'toggleStatus'])->name(('news.toggleStatus'))->middleware('check.permission:news_management');
});

Route::post('/admin/upload-temp-image', [TemporaryUploadController::class, 'store'])->name('admin.upload.temp.image')->middleware('check.permission:upload_temp_image');

// User management
Route::prefix('admin/users')->name('admin.users.')->group(function () {
    // Teacher applications
    Route::get('/teacher-applications', [TeacherApplicationController::class, 'index'])->name('teacher.applications.index')->middleware('check.permission:view_applications');
    Route::get('/teacher-applications/{id}/details', [TeacherApplicationController::class, 'showDetails'])->name('teacher.applications.details')->middleware('check.permission:view_applications');
    Route::patch('/teacher-applications/{id}/approve', [TeacherApplicationController::class, 'approve'])->name('teacher.applications.approve')->middleware('check.permission:approve_application');
    Route::patch('/teacher-applications/{id}/reject', [TeacherApplicationController::class, 'reject'])->name('teacher.applications.reject')->middleware('check.permission:reject_application');

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index')->middleware('check.permission:manage_students');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy')->middleware('check.permission:manage_students');
    Route::get('/students/{id}/details', [StudentController::class, 'show'])->name('students.details')->middleware('check.permission:manage_students');

    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index')->middleware('check.permission:manage_teachers');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy')->middleware('check.permission:manage_teachers');
    Route::get('/teachers/{id}/details', [TeacherController::class, 'show'])->name('teachers.details')->middleware('check.permission:manage_teachers');

    // Admins
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index')->middleware('check.permission:manage_admins');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy')->middleware('check.permission:manage_admins');
});

// Major Management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/majors', [MajorController::class, 'index'])->name('majors.index')->middleware('check.permission:view_majors');

    Route::get('/majors/create', [MajorController::class, 'create'])->name('majors.create')->middleware('check.permission:create_majors');
    Route::post('/majors/store', [MajorController::class, 'store'])->name('majors.store')->middleware('check.permission:create_majors');

    Route::get('/majors/{id}/edit', [MajorController::class, 'edit'])->name('majors.edit')->middleware('check.permission:edit_majors');
    Route::put('/majors/{id}', [MajorController::class, 'update'])->name('majors.update')->middleware('check.permission:edit_majors');

    Route::delete('/majors/{id}', [MajorController::class, 'destroy'])->name('majors.destroy')->middleware('check.permission:delete_majors');
});

// Event Management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index')->middleware('check.permission:view_events');

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create')->middleware('check.permission:create_events');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store')->middleware('check.permission:create_events');

    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware('check.permission:edit_events');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update')->middleware('check.permission:edit_events');

    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy')->middleware('check.permission:delete_events');

    Route::patch('/events/{id}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggleStatus')->middleware('check.permission:toggle_events');
});

// Slider Management
Route::prefix('cms')->name('cms.')->group(function () {

    Route::get('/home-page/slider/create', [SliderController::class, 'create'])->name('home-page.slider.create')->middleware('check.permission:create_homepage_slider');
    Route::post('/home-page/slider/store', [SliderController::class, 'store'])->name('home-page.slider.store')->middleware('check.permission:create_homepage_slider');

    Route::patch('/home-page/slider/{id}/toggle-status', [SliderController::class, 'toggleStatus'])->name('home-page.slider.toggleStatus')->middleware('check.permission:toggle_homepage_slider');

    Route::get('/home-page/slider', [SliderController::class, 'index'])->name('home-page.slider.index')->middleware('check.permission:view_homepage_slider');

    Route::get('/home-page/slider/{id}/edit', [SliderController::class, 'edit'])->name('home-page.slider.edit')->middleware('check.permission:edit_homepage_slider');
    Route::put('/home-page/slider/{id}', [SliderController::class, 'update'])->name('home-page.slider.update')->middleware('check.permission:edit_homepage_slider');

    Route::delete('/home-page/slider/{id}', [SliderController::class, 'destroy'])->name('home-page.slider.destroy')->middleware('check.permission:delete_homepage_slider');
});

Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('footers', [FooterController::class, 'index'])->name('footers.index')->middleware('check.permission:view_footer_section');
    Route::get('/footers/copyright/create', [FooterController::class, 'createCopyright'])->name('footers.copyright-create')->middleware('check.permission:create_footer_section');
    Route::post('/footers/copyright/store', [FooterController::class, 'store'])->name('footers.copyright.store')->middleware(['check.permission:create_footer_section', 'prevent.double.submit']);

    Route::get('/footers/main/create', [FooterController::class, 'createMain'])->name('footers.main-create')->middleware('check.permission:create_footer_section');
    Route::post('/footers/main/store', [FooterController::class, 'store'])->name('footers.main.store')->middleware(['check.permission:create_footer_section', 'prevent.double.submit']);

    Route::get('/footers/logo/create', [FooterController::class, 'createLogo'])->name('footers.logo-create')->middleware('check.permission:create_footer_section');
    Route::post('/footers/logo/store', [FooterController::class, 'store'])->name('footers.logo.store')->middleware(['check.permission:create_footer_section', 'prevent.double.submit']);

    Route::get('/footers/social/create', [FooterController::class, 'createSocial'])->name('footers.social-create')->middleware('check.permission:create_footer_section');
    Route::post('/footers/social/store', [FooterController::class, 'store'])->name('footers.social.store')->middleware(['check.permission:create_footer_section', 'prevent.double.submit']);

    Route::delete('/footers/{id}', [FooterController::class, 'destroy'])->name('footers.destroy')->middleware('check.permission:delete_footer_section');
    Route::get('/footers/{id}/edit', [FooterController::class, 'edit'])->name('footers.edit')->middleware('check.permission:edit_footer_section');
    Route::put('/footers/{id}', [FooterController::class, 'update'])->name('footers.update')->middleware('check.permission:edit_footer_section');
});

Route::middleware('guest')->group(function () {

    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/register', function () {
        return redirect()->route('home.index')->with('open_register_form', true);;
    });
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login', function () {
        return redirect()->route('home.index')->with('open_login_form', true);;
    });

    Route::get('/forgot-password', [ForgotPassWordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('/reset-password', function () {
        return redirect()->route('home.index')->with('error', 'Cannot access this page.');
    });

    Route::get('/register-teacher', [RegisterTeacherController::class, 'showForm'])->name('register-teacher.form');
    Route::post('/register-teacher', [RegisterTeacherController::class, 'register'])->name('register-teacher.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('check.permission:auth');

Route::fallback(function () {
    abort(404, 'Page Not Found');
});
