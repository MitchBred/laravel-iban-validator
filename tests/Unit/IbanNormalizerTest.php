<?php

declare(strict_types=1);

use MitchBred\LaravelIbanValidator\IbanNormalizer;

it('uppercases an IBAN and removes whitespace', function () {
    expect(IbanNormalizer::normalize("\tnl91 abna 0417\n1643 00 "))
        ->toBe('NL91ABNA0417164300');
});

it('does not remove punctuation', function () {
    expect(IbanNormalizer::normalize('nl91-abna0417164300'))
        ->toBe('NL91-ABNA0417164300');
});
