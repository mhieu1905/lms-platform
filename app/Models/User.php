<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use Notifiable;
    use Sortable;
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'cv_file',
        'status',
        'avatar',
        'phone',
        'address',
        'overview',
        'reject_reason',
        'submitted_at',
        'reviewed_at',
    ];

    public $sortable = ['id', 'name', 'email', 'status', 'submitted_at', 'reviewed_at', 'created_at', 'updated_at'];
        
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'user_id', 'id');
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'completed_lessons')
            ->withPivot(['completed_at'])
            ->withTimestamps();
    }

    public function completedCourses()
    {
        return $this->belongsToMany(Course::class, 'completed_courses')
            ->withPivot(['completed_at'])
            ->withTimestamps();
    }

    public function hasCompletedCourse($courseId): bool
    {
        return $this->completedCourses()
        ->where('course_id', $courseId)
        ->exists();
    }

    public function hasPermission($permissionName)
    {
        $permissions = $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->pluck('name')->unique();

        return $permissions->contains($permissionName);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tickets')
                    ->withPivot('cost', 'status', 'purchased_at')
                    ->withTimestamps();
    }

    public function majors()
    {
        return $this->belongsToMany(Major::class, 'major_user', 'user_id', 'major_id');
    }

    public function boughtTickets(): BelongsToMany{
        return $this->belongsToMany(Event::class, 'tickets')
            ->withPivot('purchased_at') 
            ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(\App\Models\UserActivityLog::class, 'user_id');
    }

    public function getAvgProgressAttribute()
    {
        $enrolledCourses = $this->enrolledCourses()->with('lessons')->get();
        Log::info('Enrolled courses count: ' . $enrolledCourses->count());
        if ($enrolledCourses->isEmpty()) {
            return 0;
        }

        $totalProgress = 0;

        foreach ($enrolledCourses as $course) {
            $totalLessons = $course->lessons->count();

            $completedLessons = $this->completedLessons()
                ->whereHas('chapter', function ($query) use ($course) {
                    $query->where('chapters.course_id', $course->id);
                })
                ->count();
            Log::info("Course {$course->id}: total=$totalLessons, completed=$completedLessons");

            if ($totalLessons > 0) {
                $totalProgress += ($completedLessons / $totalLessons) * 100;
            }
        }

        return round($totalProgress / $enrolledCourses->count(), 2);
    }

}
