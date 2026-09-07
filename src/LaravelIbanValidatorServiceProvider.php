<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator;

use Illuminate\Support\ServiceProvider;

final class LaravelIbanValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IbanValidator::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-iban-validator');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../lang' => $this->app->langPath('vendor/laravel-iban-validator'),
            ], 'laravel-iban-validator-translations');
        }
    }
}
