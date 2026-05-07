<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class Event extends Model
{
    use Sortable;
    protected $casts = [
        'start_time' => 'datetime',
        'finish_time' => 'datetime',
    ];

    protected $sortable = ['id', 'title', 'start_time', 'finish_time', 'total_slots', 'booked_slots', 'status'];

    public static function booted()
    {
        static::deleting(function($event) {
            if($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            };
        });
    }

    protected $fillable = [
        'title',
        'image',
        'description',
        'content',
        'status',
        'start_time',
        'finish_time',
        'address',
        'total_slots',
        'booked_slots',
        'cost',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tickets')
                    ->withPivot('price', 'status', 'purchased_at')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
