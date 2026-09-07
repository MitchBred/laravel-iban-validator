<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;
use MitchBred\LaravelIbanValidator\IbanValidator;
use MitchBred\LaravelIbanValidator\LaravelIbanValidatorServiceProvider;

it('binds the validator as a singleton', function () {
    expect(app(IbanValidator::class))->toBe(app(IbanValidator::class));
});

it('loads package translations', function () {
    expect(__('laravel-iban-validator::validation.invalid_checksum'))
        ->toBe('The :attribute has an invalid IBAN checksum.');
});

it('registers a translation publish group', function () {
    $publishedPaths = ServiceProvider::pathsToPublish(
        LaravelIbanValidatorServiceProvider::class,
        'laravel-iban-validator-translations',
    );

    expect($publishedPaths)->toHaveCount(1)
        ->and(array_values($publishedPaths))
        ->toContain($this->app->langPath('vendor/laravel-iban-validator'));
});

it('declares the service provider for package discovery', function () {
    $composerJson = file_get_contents(dirname(__DIR__, 2).'/composer.json');

    expect($composerJson)->not->toBeFalse();

    $composer = json_decode((string) $composerJson, true, flags: JSON_THROW_ON_ERROR);

    expect($composer['extra']['laravel']['providers'])
        ->toContain(LaravelIbanValidatorServiceProvider::class);
});
