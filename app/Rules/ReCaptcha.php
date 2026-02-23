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
        try {
            $response = Http::asForm()->withOptions(['verify' => false])->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
            ]);

            if (! $response->json('success')) {
                $fail('Verifikasi Google reCaptcha gagal. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            $fail('Gagal menghubungi server keamanan (Google reCaptcha). Silakan coba lagi beberapa saat.');
        }
    }
}
