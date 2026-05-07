<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class News extends Model
{
    use Sortable;
    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = ['user_id', 'category_id', 'date', 'title', 'image', 'description', 'status'];
    public $sortable = ['id', 'title', 'user_id', 'date', 'category_id', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
