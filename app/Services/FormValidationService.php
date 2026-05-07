<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class FormValidationService
{
    public static function lessonRules($request): array
    {
        $lessonId = $request->route('lesson')?->id;

        return [
            'title' => 'required|min:1|string|max:100',
            'chapter_id' => 'required|exists:chapters,id',
            'course_id' => 'required|exists:courses,id',
            'video' => 'mimes:mp4,avi,mov,mkv,webm|max:51200',
            'content' => 'required|min:1',
            'duration' => ['required', 'integer', 'min:1', 'max:999'],
            'status' => 'required',


        ];
    }

    public static function chapterRules($request): array
    {

        return [
            'title' => 'required|min:1 |string|max:100',
            'course_id' => 'required|exists:courses,id',
        ];
    }

    public static function requiredLevelName($request): array    {

        return [
            'name' => 'required|unique:levels|min:1|string|max:100',
        ];
    }

    public static function requiredCategoryName($request): array
    {
        return [
            'name' => 'required|unique:categories|min:1|string|max:100',
        ];
    }

    public static function footerRules($request, $isUpdate = false, $key): \Illuminate\Contracts\Validation\Validator
    {
        $logoRule = $isUpdate ? 'nullable' : 'required';
        $rules = [
            'socials' => 'nullable',
            'items' => 'sometimes|required|array',
            'items.*.label' => 'required|string',
            'items.*.link' => 'sometimes|required|string|url',
            'items.*.text' => 'sometimes|required|string',

        ];

        if ($key === '2') {
            $rules['logo'] = $logoRule . '|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        } elseif ($key === '1') {
            $rules['title'] = 'required|string|min:1';
        } elseif ($key === '3') {
            $rules['copyright'] = 'required|string|min:1';
        } elseif ($key === '4') {
            $rules['items'] = 'required|array';
        }

        $messages = [
            'title' => 'Title Field Is Required',
            'items.*.label.required' => 'Item Label Field Is Required',
            'items.*.link.required' => 'Item Link Field Is Required',
            'items.*.link.url' => 'Item Link Field Must Be Link',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
    public static function newsRule($request, $isUpdate = false): array
    {
        return [
            'title' => 'required|string|min:1',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'image' => ($isUpdate ? 'required' : 'nullable') . '|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'required|string|min:1'
        ];
    }
}
