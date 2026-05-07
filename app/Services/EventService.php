<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EventService
{
    /**
     * Trim a string and replace multiple spaces with a single space.
     *
     * @param string $value
     * @return string
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function cleanInput($value)
    {
        return preg_replace('/\s+/', ' ', trim($value));
    }

    /**
     * Clean all string values in an array, including JSON content arrays.
     *
     * @param array $data
     * @return array
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function cleanRequestData(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'content_json' && self::isJson($value)) {
                $decoded = json_decode($value, true);
                $decoded = array_map(fn($item) => self::cleanInput($item), $decoded);
                $decoded = array_filter($decoded, fn($item) => $item !== "");
                $data[$key] = json_encode($decoded);
            } elseif (is_string($value)) {
                $data[$key] = self::cleanInput($value);
            }
        }

        return $data;
    }

    /**
     * Check if a string is a valid JSON format.
     *
     * @param string $string
     * @return bool
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate event request data for creation or update.
     * 
     * Checks required fields including JSON content.
     * Image is required only when creating a new event, optional when updating.
     *
     * @param \Illuminate\Http\Request $request
     * @param bool $isUpdate
     * @return \Illuminate\Contracts\Validation\Validator
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function validate(Request $request, $isUpdate = false)
    {
        $cleaned = self::cleanRequestData($request->all());

        $rules = [
            'title' => 'required|string|max:70',
            'description' => 'required|string',
            'content_json' => [
                'required',
                function ($attribute, $value, $fail) {
                    $decoded = json_decode($value, true);
                    if (!self::isJson($value)) {
                        $fail('The content field must be valid JSON.');
                    }

                    if (empty($decoded)) {
                        $fail('The content field is required.');
                    }
                },
            ],
            'address' => 'required|string|max:70',
            'total_slots' => 'required|integer|max:1000',
            'cost' => 'required|numeric|lt:1000000|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|in:0,1',
            'start_time' => 'required|date',
            'finish_time' => 'required|date|after:start_time',
        ];
        $messages = [
            'content_json.required' => 'The content field is required.',
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

        return Validator::make($cleaned, $rules, $messages);
    }

    /**
     * Update an event's data with new request values.
     * 
     * @param \App\Models\Event $event
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function updateEvent(Event $event, Request $request)
    {
        $cleaned = self::cleanRequestData($request->all());

        $dataToUpdate = [];

        $fieldToCheck = [
            'title',
            'description',
            'content_json',
            'address',
            'total_slots',
            'cost',
            'status',
            'start_time',
            'finish_time',
        ];

        foreach ($fieldToCheck as $field) {
            $newValue = $cleaned[$field] ?? null;
            if ((string) $event->$field != (string) $newValue) {
                $dataToUpdate[$field] = $newValue;
            }
        }

        if (!empty($cleaned['image_path'])) {
            $tempPath = str_replace('/storage/', '', $cleaned['image_path']);
            $disk = Storage::disk('public');

            if ($event->image && $disk->exists($event->image)) {
                $disk->delete($event->image);
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

            if (!$disk->exists('uploads/events')) {
                $disk->makeDirectory('uploads/events');
            }

            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $newPath = 'uploads/events/' . $newName;

            Storage::disk('public')->move($tempPath, $newPath);

            $dataToUpdate['image'] = $newPath;
        }

        if (!empty($dataToUpdate)) {
            if (isset($dataToUpdate['content_json'])) {
                $dataToUpdate['content'] = $dataToUpdate['content_json'];
                unset($dataToUpdate['content_json']);
            }

            $event->update($dataToUpdate);
        }

        return $dataToUpdate;
    }

    /**
     * Get all upcoming events that have not started yet.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function showEventUpcoming()
    {
        $now = now();
        $events = Event::where('start_time', '>', $now)
                        ->where('status', 1)
                        ->orderBy('start_time', 'asc')
                        ->get();
        
        return $events;
    }

    /**
     * Get all events currently happening.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function showEventHappening()
    {
        $now = now();
        $events = Event::where('start_time', '<=', $now)
                        ->where('finish_time', '>=', $now)
                        ->where('status', 1)
                        ->orderBy('finish_time', 'asc')
                        ->orderBy('start_time', 'asc')
                        ->get();
        
        return $events;
    }

    /**
     * Get all events that have already ended.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function showEventExpired()
    {
        $now = now();
        $events = Event::where('finish_time', '<', $now)
                        ->where('status', 1)
                        ->orderBy('finish_time', 'asc')
                        ->get();
        
        return $events;
    }

    /**
     * Get detailed information of a specific event by ID.
     *
     * @param int $id
     * @return \App\Models\Event
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public static function showEventsDetails($id)
    {
        $event = Event::findOrFail($id);

        return $event;
    }
}
