<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator;

final readonly class IbanValidationResult
{
    private function __construct(
        public string $normalized,
        public ?IbanValidationFailure $failure,
    ) {}

    public static function passed(string $normalized): self
    {
        return new self($normalized, null);
    }

    public static function failed(string $normalized, IbanValidationFailure $failure): self
    {
        return new self($normalized, $failure);
    }

    public function passes(): bool
    {
        return $this->failure === null;
    }
}
