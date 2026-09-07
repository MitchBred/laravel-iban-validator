<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use MitchBred\LaravelIbanValidator\IbanValidationFailure;
use MitchBred\LaravelIbanValidator\IbanValidator;

final class Iban implements ValidationRule
{
    private readonly IbanValidator $validator;

    /**
     * @param  array<array-key, mixed>  $allowedCountries
     */
    public function __construct(
        private readonly array $allowedCountries = [],
        ?IbanValidator $validator = null,
    ) {
        $this->validator = $validator ?? new IbanValidator;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('laravel-iban-validator::validation.invalid_format')->translate();

            return;
        }

        $result = $this->validator->validate($value, $this->allowedCountries);

        if ($result->failure === null) {
            return;
        }

        $translationKey = match ($result->failure) {
            IbanValidationFailure::UnsupportedCountry => 'unsupported_country',
            IbanValidationFailure::CountryNotAllowed => 'country_not_allowed',
            IbanValidationFailure::InvalidLength => 'invalid_length',
            IbanValidationFailure::InvalidFormat => 'invalid_format',
            IbanValidationFailure::InvalidChecksum => 'invalid_checksum',
        };

        $fail("laravel-iban-validator::validation.{$translationKey}")->translate();
    }
}
