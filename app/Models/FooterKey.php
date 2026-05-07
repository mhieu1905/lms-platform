<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterKey extends Model
{
    use HasFactory;

    protected $table = 'footer_keys';

    protected $fillable = ['name'];

    public function sections()
    {
        return $this->hasMany(FooterSection::class, 'key_id');
    }
}
