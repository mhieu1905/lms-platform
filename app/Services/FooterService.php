<?php

namespace App\Services;

use App\Models\HomePage\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FooterService
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

}
