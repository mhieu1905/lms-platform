<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    public static function cleanRequestData(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = self::cleanInput($value);
            }
        }

        return $data;
    }
    public static function updateNews(News $news, Request $request)
    {
        $fieldToCheck = [
            'title',
            'category_id',
            'description',
        ];


        $dataToUpdate = [];

        foreach ($fieldToCheck as $field) {
            $newValue = $request->input($field);
            $currentValue = $news->$field;

            if ($newValue !=   $currentValue) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists('uploads/news/' . $news->image)) {
                Storage::disk('public')->delete('uploads/news/' . $news->image);
            }

            $path = $request->file('image')->store('uploads/news', 'public');
            $dataToUpdate['image'] = basename($path);
        }

        if (!empty($dataToUpdate)) {
            $news->update($dataToUpdate);
        }
        return $dataToUpdate;
    }
}
