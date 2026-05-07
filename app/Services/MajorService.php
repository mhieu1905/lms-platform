<?php

namespace App\Services;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MajorService
{
    /**
     * Clean up excess space
     * 
     * @param mixed $value
     * @return array|string|null
     * 
     * @author Ho Luu Duc
     * Date: 27-08-2025
     */
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

    public static function validate(Request $request)
    {
        $cleaned = self::cleanRequestData($request->all());

        $rules = [
            'name' => 'required|string|max:50|unique:majors,name',
        ];

        return Validator::make($cleaned, $rules);
    }

    /**
     * Update a major's data with new values.
     * @param \App\Models\Major $major
     * @param \Illuminate\Http\Request $request
     * @return array|Major
     * 
     * @author Ho Luu Duc
     * Date: 27-08-2025
     */
    public static function updateMajor(Major $major, Request $request)
    {
        $newValue = self::cleanInput($request->input('name', ''));

        if ($newValue != $major->name) {
            $major->update(['name' => $newValue]);
            return $major;
        }
        return [];
    }
}