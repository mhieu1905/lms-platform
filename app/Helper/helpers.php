<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Str;

if (!function_exists('safe_json_decode')) {
    /**
     * Decode JSON arrays safely.
     * 
     * Description: Converts a JSON string into an array. Returns a default value if JSON is invalid.
     * 
     * @param string|null $jsonString The JSON string to decode.
     * @param array $default Return value if JSON is invalid
     * @return array Decoded array or $default if invalid.
     * 
     * @author Ho Luu Duc
     * Date created: 20-08-2025
     */
    function safe_json_decode(?string $jsonString, array $default = []): array
    {
        if (!$jsonString) {
            return $default;
        }

        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $default;
        }

        return $data;
    }

    /**
     * Generate a shortened string suitable for the alt attribute of an <img> tag.
     * 
     * This function first limits the text by word count (to avoid breaking words),
     * then further restricts it by character length to ensure the final result
     * does not exceed the maximum allowed size.
     * 
     * @param string $text   The original text (e.g., an event title or description).
     * @param int    $words  Maximum number of words allowed (default: 10).
     * @param int    $limit  Maximum number of characters allowed (default: 100).
     * @return string The shortened string, safe to use as an image alt attribute.
     * 
     * @author Ho Luu Duc
     * Date created: 21-08-2025
     */
    function generateAlt($text, $words = 10, $limit = 100)
    {
        $alt = Str::words($text, $words, '');
        return Str::limit($alt, $limit, '');
    }

}
