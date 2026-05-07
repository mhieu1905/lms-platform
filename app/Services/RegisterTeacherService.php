<?php

namespace App\Services;

use App\Models\User;
use App\Rules\ValidTld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterTeacherService
{
    /**
     * Clean up excess space.
     * 
     * @param mixed $value
     * @return array|string|null
     * 
     * @author Ho Luu Duc
     * Date: 29-08-2025
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

    /**
     * Validate register request data for creation or update.
     * @param \Illuminate\Http\Request $request
     * @param mixed $isUpdate
     * @return \Illuminate\Validation\Validator
     * 
     * @author Ho Luu Duc
     * Date: 29-08-2025
     */
    public static function validate(Request $request, $isUpdate = false)
    {
        $cleaned = self::cleanRequestData($request->all());

        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^(?=.{2,50}$)(?:[A-Za-zÀ-ỹ]+(?:(?:[ ]+)|(?:[ ]*[\'-][ ]*)))*[A-Za-zÀ-ỹ]+$/'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'max:64',
                'regex:/^(?=.{1,64}$)(?!\d+\.\d+)[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*@[a-zA-Z]+(?:(?:\-[a-zA-Z]+)|(?:\.[a-zA-Z]+))*(?:\.[a-zA-Z]+)+$/',
                new ValidTld,
            ],
            'password' => [
                'required',
                'string',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&~`*()_+\-=\[\]{}?\/><])\S{8,}$/',
                'confirmed'
            ],
            'majors' => 'required|array|min:1|max:3',
            'majors.*' => 'nullable|integer|exists:majors,id',
        ];

        $messages = [
            'majors.*.exists'  => 'The selected major does not exist in the system.', 
        ];

        if ($isUpdate) {
            $rules['cv_file'] = 'nullable|mimes:pdf|max:2048';
        } else {
            $rules['cv_file'] = 'required|mimes:pdf|max:2048';
        }

        return Validator::make($cleaned, $rules, $messages);
    }

    /**
     * Update teacher profile.
     * 
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return array<array|bool|mixed|string|null>
     * 
     * @author Ho Luu Duc
     * Date: 29-08-2025
     */
    public static function updateProfile(User $user, Request $request)
    {
        $cleaned = self::cleanRequestData($request->all());

        $fieldToCheck = [
            'name',
        ];

        $dataToUpdate = [];

        foreach ($fieldToCheck as $field) {
            $newValue = $cleaned[$field] ?? null;

            if ((string) $newValue != (string) $user->$field) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if ($request->hasFile('cv_file')) {
            if ($user->cv_file && Storage::disk('public')->exists($user->cv_file)) {
                Storage::disk('public')->delete($user->cv_file);
            }

            if (!Storage::disk('public')->exists('uploads/cvfiles')) {
                Storage::disk('public')->makeDirectory('uploads/cvfiles');
            }

            $newCvPath = $request->file('cv_file')->store('uploads/cvfiles', 'public');
            $dataToUpdate['cv_file'] = $newCvPath;
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
        }

        return $dataToUpdate;
    }
}
