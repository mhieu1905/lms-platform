<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTld implements ValidationRule
{
    protected array $tlds;

    public function __construct()
    {
        // Load TLD list from IANA file (download and save in storage/app)
        $file = storage_path('app/tlds-alpha-by-domain.txt');
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Remove the first line ("# Version ...")
        $this->tlds = array_slice($lines, 1);
    }

    /**
     * Validate the given attribute.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parts = explode('.', strtolower($value));
        $tld = strtoupper(end($parts));

        if (!in_array($tld, $this->tlds)) {
            $fail('Invalid email domain.');
        }
    }
}
