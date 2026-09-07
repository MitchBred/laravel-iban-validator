<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator;

use InvalidArgumentException;
use MitchBred\LaravelIbanValidator\Support\CountryCodeLengths;

final class IbanValidator
{
    /**
     * @param  array<array-key, mixed>  $allowedCountries
     */
    public function validate(string $value, array $allowedCountries = []): IbanValidationResult
    {
        $normalized = IbanNormalizer::normalize($value);
        $allowedCountryLookup = $this->allowedCountryLookup($allowedCountries);

        if (preg_match('/^([A-Z]{2})(\d{2})/', $normalized, $matches) !== 1) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::InvalidFormat);
        }

        $countryCode = $matches[1];
        $expectedLength = CountryCodeLengths::length($countryCode);

        if ($expectedLength === null) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::UnsupportedCountry);
        }

        if (strlen($normalized) !== $expectedLength) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::InvalidLength);
        }

        if (preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}\z/', $normalized) !== 1) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::InvalidFormat);
        }

        if ($this->mod97($normalized) !== 1) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::InvalidChecksum);
        }

        if ($allowedCountryLookup !== [] && ! isset($allowedCountryLookup[$countryCode])) {
            return IbanValidationResult::failed($normalized, IbanValidationFailure::CountryNotAllowed);
        }

        return IbanValidationResult::passed($normalized);
    }

    /**
     * @param  array<array-key, mixed>  $allowedCountries
     * @return array<string, true>
     */
    private function allowedCountryLookup(array $allowedCountries): array
    {
        $lookup = [];

        foreach ($allowedCountries as $countryCode) {
            if (! is_string($countryCode)) {
                throw new InvalidArgumentException('Allowed country codes must be strings.');
            }

            $normalizedCountryCode = strtoupper(trim($countryCode));

            if (preg_match('/^[A-Z]{2}\z/', $normalizedCountryCode) !== 1
                || CountryCodeLengths::length($normalizedCountryCode) === null) {
                throw new InvalidArgumentException("Unsupported allowed country code [{$countryCode}].");
            }

            $lookup[$normalizedCountryCode] = true;
        }

        return $lookup;
    }

    private function mod97(string $iban): int
    {
        $rearranged = substr($iban, 4).substr($iban, 0, 4);
        $remainder = 0;

        foreach (str_split($rearranged) as $character) {
            $digits = $character >= 'A' && $character <= 'Z'
                ? (string) (ord($character) - 55)
                : $character;

            foreach (str_split($digits) as $digit) {
                $remainder = (($remainder * 10) + (int) $digit) % 97;
            }
        }

        return $remainder;
    }
}
