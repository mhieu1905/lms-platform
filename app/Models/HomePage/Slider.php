<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class Slider extends Model
{
    use Sortable;
    protected $fillable = ['title', 'subtitle', 'image', 'button_text', 'button_link', 'regular_price', 'sale_price', 'date_end', 'status'];
    public $sortable = ['id', 'title', 'subtitle', 'button_text', 'status'];
    protected static function booted()
    {
        static::deleting(function($slider) {
            if($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
        });
    }
}
