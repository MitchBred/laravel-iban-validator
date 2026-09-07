![Laravel IBAN Validator](https://banners.beyondco.de/Laravel%20IBAN%20Validator.png?theme=light&packageManager=composer+require&packageName=mitchbred%2Flaravel-iban-validator&pattern=architect&style=style_1&description=IBAN+length+and+checksum+validation+for+Laravel&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

[![Tests](https://github.com/MitchBred/laravel-iban-validator/actions/workflows/tests.yml/badge.svg)](https://github.com/MitchBred/laravel-iban-validator/actions/workflows/tests.yml)

IBAN length and checksum validation for Laravel 13 and PHP 8.5. The package
validates a recognized country code, country-specific total length, generic
IBAN format, allowed-country policies, and the ISO 13616 mod-97 checksum.

The validator has no runtime dependency beyond the Illuminate components used
by Laravel.

## Requirements

- PHP 8.5 or newer
- Laravel 13.12 or newer

## Installation

```bash
composer require mitchbred/laravel-iban-validator
```

Laravel discovers the package service provider automatically.

## Core validator

```php
use MitchBred\LaravelIbanValidator\IbanValidator;

$result = (new IbanValidator)->validate('nl91 abna 0417 1643 00');

$result->passes();    // true
$result->normalized;  // NL91ABNA0417164300
$result->failure;     // null
```

Validation always returns an `IbanValidationResult`. Invalid values expose one
`IbanValidationFailure`:

- `UnsupportedCountry`
- `CountryNotAllowed`
- `InvalidLength`
- `InvalidFormat`
- `InvalidChecksum`

```php
use MitchBred\LaravelIbanValidator\IbanValidationFailure;
use MitchBred\LaravelIbanValidator\IbanValidator;

$result = (new IbanValidator)->validate('NL01BANK0123456789');

$result->passes(); // false
$result->failure === IbanValidationFailure::InvalidChecksum; // true
```

## Restricting countries

The core validator accepts every country included in the package by default.
Pass an allow-list when an application accepts only specific countries:

```php
$result = (new IbanValidator)->validate(
    value: 'NL91ABNA0417164300',
    allowedCountries: ['NL', 'BE'],
);
```

Country codes are trimmed and uppercased. An unsupported configured country
code throws `InvalidArgumentException`, because that is a developer
configuration error rather than invalid user input.

## Laravel validation rule

```php
use MitchBred\LaravelIbanValidator\Rules\Iban;

public function rules(): array
{
    return [
        'iban' => ['required', 'string', new Iban],
        'dutch_iban' => [
            'required',
            'string',
            new Iban(allowedCountries: ['NL']),
        ],
    ];
}
```

The rule is intentionally not implicit. Combine it with Laravel's `required`,
`nullable`, `sometimes`, and `string` rules to define presence and type
requirements.

The rule normalizes a value for validation, but it does not mutate request
data. Use `IbanNormalizer` when the canonical value must be stored:

```php
use MitchBred\LaravelIbanValidator\IbanNormalizer;

$canonicalIban = IbanNormalizer::normalize('nl91 abna 0417 1643 00');
// NL91ABNA0417164300
```

## Normalization

Normalization:

1. converts ASCII letters to uppercase;
2. removes whitespace anywhere in the value.

It does not remove punctuation. For example,
`NL91-ABNA0417164300` stays punctuated and fails validation.

## Translations

English and Dutch validation messages are included. Laravel uses the current
application locale.

Publish the language files when an application needs custom messages:

```bash
php artisan vendor:publish --tag=laravel-iban-validator-translations
```

Laravel writes them to:

```text
lang/vendor/laravel-iban-validator
```

## Recognized country formats

The package contains 111 country-code formats from
`ngx-iban-validator` v1.2.4. See [SUPPORTED_COUNTRIES.md](SUPPORTED_COUNTRIES.md).

This list mirrors that upstream release. It is not presented as a live or
authoritative copy of the SWIFT IBAN Registry.

## Testing

```bash
composer check
```

The test suite validates all 111 upstream examples and covers normalization,
failure reasons, country restrictions, translations, package discovery,
formatting, and static analysis.

## Attribution

The validation algorithm, country lengths, and valid IBAN examples were ported
from [`SKaDiZZ/ngx-iban-validator`](https://github.com/SKaDiZZ/ngx-iban-validator)
v1.2.4 under the MIT License. See
[THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).

## License

Laravel IBAN Validator is open-source software licensed under the
[MIT License](LICENSE).
