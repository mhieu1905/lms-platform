<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Major extends Model
{
    use Sortable;
    protected $fillable = ['name'];
    public $sortable = ['id', 'name'];
    public function users() 
    {
        return $this->belongsToMany(User::class, 'major_user', 'user_id', 'major_id');
    }
}
