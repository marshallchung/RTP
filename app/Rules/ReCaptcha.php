<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => config('google-recaptcha.secret'),
            'response' => $value
        ]);

	$responseBody = $response->json();
       if (!$responseBody["success"]) {
        $errorCodes = $responseBody["error-codes"] ?? [];
        Log::error('reCAPTCHA verification failed', [
            'attribute' => $attribute,
            'response' => $value,
            'error_codes' => $errorCodes
        ]);

  
        return false;
    }
        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The google recaptcha is required.';
    }
}
