<?php

namespace App\Services;

use App\Models\HomePage\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SliderService
{
    // clean up excess space
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

    public static function validate(Request $request, $isUpdate = false)
    {
        $cleaned = self::cleanRequestData($request->all());

        $rules = [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'button_link' => [
                'required',
                'string',
                'max:255',
                'regex:/^(https?:\/\/\S+|(\/\S+)|(\.{1,2}\/\S+))$/'
            ],
            'status' => 'required|in:0,1',
            'regular_price' => 'nullable|numeric|min:0|lt:1000000',
            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($cleaned) {
                    // Check if regular_price exists in the data
                    if (array_key_exists('regular_price', $cleaned)) {
                        $regularPrice = $cleaned['regular_price'];

                        // Check if sale_price has a value and is greater than or equal to regular_price
                        if ($value !== null && $value >= $regularPrice) {
                            $fail('The sale price must be less than the regular price.');
                        }
                    }
                },
            ],
            'date_end' => 'nullable|date|after:today',
        ];

        if ($isUpdate) {
            $rules['image_path'] = 'nullable|string';
        } else {
            $rules['image_path'] = 'required|string';
        }

        if (!$isUpdate && !$request->filled('image_path')) {
            $validator = Validator::make([], []);
            $validator->errors()->add('image', 'Please upload an image.');
            throw new ValidationException($validator);
        }

        return Validator::make($cleaned, $rules);
    }

    public static function updateSlider(Slider $slider, Request $request)
    {
        $cleaned = self::cleanRequestData($request->all());

        $fieldToCheck = [
            'title',
            'subtitle',
            'button_text',
            'button_link',
            'status',
            'regular_price',
            'sale_price',
            'date_end',
        ];

        $dataToUpdate = [];

        foreach ($fieldToCheck as $field) {
            $newValue = $cleaned[$field] ?? null;

            if ((string) $newValue != (string) $slider->$field) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if (!empty($cleaned['image_path'])) {
            $tempPath = str_replace('/storage/', '', $cleaned['image_path']);
            $disk = Storage::disk('public');

            if ($slider->image && $disk->exists($slider->image)) {
                $disk->delete($slider->image);
            }

            if (!$disk->exists($tempPath)) {
                return back()->withErrors(['image' => 'File not found'])->withInput();
            }

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $mime = $disk->mimeType($tempPath);
            $validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
            if(!in_array($mime, $validTypes)) {
                return back()->withErrors(['image' => 'File type is not allowed'])->withInput();
            }

            if (!$disk->exists('uploads/sliders')) {
                $disk->makeDirectory('uploads/sliders');
            }

            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $newPath = 'uploads/sliders/' . $newName;

            Storage::disk('public')->move($tempPath, $newPath);

            $dataToUpdate['image'] = $newPath;
        }

        if (!empty($dataToUpdate)) {
            $slider->update($dataToUpdate);
        }

        return $dataToUpdate;
    }
}
