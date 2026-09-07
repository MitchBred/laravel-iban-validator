<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use MitchBred\LaravelIbanValidator\Rules\Iban;

it('passes a valid IBAN', function () {
    $validator = Validator::make(
        ['iban' => 'NL91ABNA0417164300'],
        ['iban' => ['required', 'string', new Iban]],
    );

    expect($validator->passes())->toBeTrue();
});

it('fails a checksum-invalid IBAN with an English message', function () {
    $validator = Validator::make(
        ['iban' => 'NL01BANK0123456789'],
        ['iban' => ['required', 'string', new Iban]],
    );

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('iban'))
        ->toBe('The iban has an invalid IBAN checksum.');
});

it('uses the Dutch translation', function () {
    app()->setLocale('nl');

    $validator = Validator::make(
        ['iban' => 'NL01BANK0123456789'],
        ['iban' => ['required', 'string', new Iban]],
    );

    expect($validator->errors()->first('iban'))
        ->toBe('iban heeft een ongeldige IBAN-controlewaarde.');
});

it('restricts allowed countries', function () {
    $validator = Validator::make(
        ['iban' => 'AT483200000012345864'],
        ['iban' => [new Iban(allowedCountries: ['NL'])]],
    );

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('iban'))
        ->toBe('The iban must use an allowed IBAN country code.');
});

it('fails non-string values without throwing', function () {
    $validator = Validator::make(
        ['iban' => ['NL91ABNA0417164300']],
        ['iban' => [new Iban]],
    );

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('iban'))
        ->toBe('The iban must be a valid IBAN.');
});

it('leaves null handling to nullable', function () {
    $validator = Validator::make(
        ['iban' => null],
        ['iban' => ['nullable', new Iban]],
    );

    expect($validator->passes())->toBeTrue();
});

it('leaves presence handling to required', function () {
    $validator = Validator::make(
        [],
        ['iban' => ['required', new Iban]],
    );

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->first('iban'))
        ->toBe('The iban field is required.');
});
