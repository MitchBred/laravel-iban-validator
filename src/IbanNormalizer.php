<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator;

use RuntimeException;

final class IbanNormalizer
{
    public static function normalize(string $value): string
    {
        $normalized = preg_replace('/\s+/', '', strtoupper($value));

        if ($normalized === null) {
            throw new RuntimeException('Unable to normalize the IBAN.');
        }

        return $normalized;
    }
}
