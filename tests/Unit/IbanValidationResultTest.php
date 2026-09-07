<?php

declare(strict_types=1);

use MitchBred\LaravelIbanValidator\IbanValidationFailure;
use MitchBred\LaravelIbanValidator\IbanValidationResult;

it('represents a passed validation', function () {
    $result = IbanValidationResult::passed('NL91ABNA0417164300');

    expect($result->passes())->toBeTrue()
        ->and($result->normalized)->toBe('NL91ABNA0417164300')
        ->and($result->failure)->toBeNull();
});

it('represents a failed validation', function () {
    $result = IbanValidationResult::failed(
        'NL01BANK0123456789',
        IbanValidationFailure::InvalidChecksum,
    );

    expect($result->passes())->toBeFalse()
        ->and($result->failure)->toBe(IbanValidationFailure::InvalidChecksum);
});

it('is readonly', function () {
    expect((new ReflectionClass(IbanValidationResult::class))->isReadOnly())->toBeTrue();
});
