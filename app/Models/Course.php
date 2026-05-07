<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory;
    use Sortable;
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'duration',
        'language',
        'regular_price',
        'sale_price',
        'status',
        'level_id',
        'category_id',
        'user_id',
        'image',
    ];

    public $sortable = ['id', 'title', 'category_id', 'level_id', 'status'];

    const STATUS_PENDING = 0;
    const STATUS_PUBLISHING = 1;
    const STATUS_HIDDEN = 2;

    public static $statusLabels = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PUBLISHING => 'Publishing',
        self::STATUS_HIDDEN => 'Hidden',
    ];

    protected static function booted()
    {
        static::deleting(function ($course) {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function chapter()
    {
        return $this->hasMany(Chapter::class, 'course_id', 'id');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    public function language()
    {
        return $this->belongsTo(Chapter::class, 'course_id', 'id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'course_id', 'id');
    }

    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function orderDetails()
    {
        return $this->morphMany(OrderDetail::class, 'product');
    }
}
