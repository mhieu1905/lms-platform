<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Chapter extends Model
{
    use Sortable;

    protected $fillable = ['title', 'course_id'];
    protected $sortable = ['id', 'title', 'course_id'];
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'chapter_id', 'id')->orderBy('order');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }
}
