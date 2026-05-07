<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Level extends Model
{
    use Sortable;
    protected $fillable = ['name'];
    public $sortable = ['id', 'name'];
    public function courses(){
        return $this->hasMany(Course::class);
    }
}
