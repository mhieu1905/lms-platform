<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }
    public static function updateCategory(Category $category, Request $request)
    {
        $newValue = self::cleanInput($request->input('name', ''));

        if ($newValue !== $category->name) {
            $category->update(['name' => $newValue]);
            return ['name' => $newValue];
        }

        return [];
    }
}
