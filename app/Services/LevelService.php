<?php

namespace App\Services;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelService
{
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }
    public static function updateLevel(Level $level, Request $request)
    {

        $newValue = self::cleanInput($request->input('name', ''));

        if ($newValue != $level->name) {
            $level->update(['name' => $newValue]);
            return ['name' => $newValue];
        }
        return [];
    }
}
