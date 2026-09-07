<?php

declare(strict_types=1);

use MitchBred\LaravelIbanValidator\IbanValidationFailure;
use MitchBred\LaravelIbanValidator\IbanValidator;
use MitchBred\LaravelIbanValidator\Support\CountryCodeLengths;

use function MitchBred\LaravelIbanValidator\Tests\Datasets\validIbanExamples;

it('accepts every valid upstream example', function (string $iban) {
    $result = (new IbanValidator)->validate($iban);

    expect($result->passes())->toBeTrue()
        ->and($result->normalized)->toBe($iban)
        ->and($result->failure)->toBeNull();
})->with('valid ibans');

it('keeps the country map and examples aligned', function () {
    $examples = validIbanExamples();
    $countryLengths = CountryCodeLengths::all();

    expect($examples)->toHaveCount(111)
        ->and($countryLengths)->toHaveCount(111)
        ->and(array_keys($examples))->toBe(array_keys($countryLengths));
});

it('reports the precise validation failure', function (
    string $iban,
    IbanValidationFailure $failure,
    array $allowedCountries,
) {
    $result = (new IbanValidator)->validate($iban, $allowedCountries);

    expect($result->passes())->toBeFalse()
        ->and($result->failure)->toBe($failure);
})->with([
    'unsupported country' => [
        'ZZ11123456789012345678',
        IbanValidationFailure::UnsupportedCountry,
        [],
    ],
    'country not allowed' => [
        'AT483200000012345864',
        IbanValidationFailure::CountryNotAllowed,
        ['NL'],
    ],
    'invalid length' => [
        'AT1112345678901234567',
        IbanValidationFailure::InvalidLength,
        [],
    ],
    'invalid format' => [
        'NL91ABNA04171643-0',
        IbanValidationFailure::InvalidFormat,
        [],
    ],
    'invalid checksum' => [
        'NL01BANK0123456789',
        IbanValidationFailure::InvalidChecksum,
        [],
    ],
]);

it('normalizes before validating', function () {
    $result = (new IbanValidator)->validate('nl91 abna 0417 1643 00');

    expect($result->passes())->toBeTrue()
        ->and($result->normalized)->toBe('NL91ABNA0417164300');
});

it('reports intrinsic failures before allowed-country policy failures', function () {
    $result = (new IbanValidator)->validate('AT003200000012345864', ['NL']);

    expect($result->failure)->toBe(IbanValidationFailure::InvalidChecksum);
});

it('accepts normalized allowed country configuration', function () {
    $result = (new IbanValidator)->validate('NL91ABNA0417164300', [' nl ']);

    expect($result->passes())->toBeTrue();
});

it('rejects unsupported allowed country configuration', function () {
    expect(fn () => (new IbanValidator)->validate('NL91ABNA0417164300', ['ZZ']))
        ->toThrow(InvalidArgumentException::class, 'Unsupported allowed country code [ZZ].');
});

it('rejects non-string allowed country configuration', function () {
    expect(fn () => (new IbanValidator)->validate('NL91ABNA0417164300', [123]))
        ->toThrow(InvalidArgumentException::class, 'Allowed country codes must be strings.');
});

it('treats an empty value as an invalid format', function () {
    $result = (new IbanValidator)->validate('');

    expect($result->failure)->toBe(IbanValidationFailure::InvalidFormat);
});
