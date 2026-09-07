<?php

declare(strict_types=1);

namespace MitchBred\LaravelIbanValidator;

enum IbanValidationFailure: string
{
    case UnsupportedCountry = 'unsupported_country';
    case CountryNotAllowed = 'country_not_allowed';
    case InvalidLength = 'invalid_length';
    case InvalidFormat = 'invalid_format';
    case InvalidChecksum = 'invalid_checksum';
}
