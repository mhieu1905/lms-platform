<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class FooterSection extends Model
{
    use HasFactory;
    use Sortable;
    protected $fillable = ['key_id','content',];
    protected $sortable = ['id', 'key_id'];
    public function key()
    {
        return $this->belongsTo(FooterKey::class, 'key_id');
    }
}
