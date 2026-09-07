<?php

declare(strict_types=1);

arch('source uses strict types')
    ->expect('MitchBred\LaravelIbanValidator')
    ->toUseStrictTypes();

arch('source avoids debug helpers')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();
