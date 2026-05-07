<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Lesson extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'lessons';
    protected $fillable = ['chapter_id', 'title', 'video', 'content', 'duration', 'status', 'order'];
    public $sortable = ['id', 'title', 'chapter_id', 'status', 'duration'];
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'id');
    }
    public function userCompletedLesson()
    {
        return $this->belongsToMany(User::class, 'completed_lessons')
            ->withPivot(['completed_at'])
            ->withTimestamps();
    }
}
