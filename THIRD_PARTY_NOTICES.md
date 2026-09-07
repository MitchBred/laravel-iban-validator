# Third-party notices

## `SKaDiZZ/ngx-iban-validator`

Parts of this package are derived from
[`SKaDiZZ/ngx-iban-validator`](https://github.com/SKaDiZZ/ngx-iban-validator):

- validation flow and mod-97 algorithm from
  `packages/ngx-iban-validator/src/iban.validator.ts`;
- country-code lengths from
  `packages/ngx-iban-validator/src/code-lengths.ts`;
- valid IBAN examples from
  `packages/ngx-iban-validator/src/__tests__/iban.validator.spec.ts`;
- country names from `SUPPORTED_COUNTRIES.md`.

- Source release: `v1.2.4`
- Source commit: `6d4b1c47f8820fbd7354a74c36b3b79142a2ab8a`

The implementation was rewritten in PHP and intentionally differs in several
ways:

- it removes whitespace rather than every non-alphanumeric character;
- it always returns a structured result;
- it distinguishes invalid format from invalid checksum;
- it supports explicit allowed-country policies;
- it provides a Laravel validation rule and translations.

The upstream source is distributed under the following license:

> MIT License
>
> Copyright (c) 2025 Samir Kahvedzic
>
> Permission is hereby granted, free of charge, to any person obtaining a copy
> of this software and associated documentation files (the "Software"), to deal
> in the Software without restriction, including without limitation the rights
> to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
> copies of the Software, and to permit persons to whom the Software is
> furnished to do so, subject to the following conditions:
>
> The above copyright notice and this permission notice shall be included in all
> copies or substantial portions of the Software.
>
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
> IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
> FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
> AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
> LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
> OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
> SOFTWARE.
