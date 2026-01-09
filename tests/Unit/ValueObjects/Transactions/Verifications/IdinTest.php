<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Idin;

it('returns the correct type', function () {
    $idin = new Idin;
    expect($idin->getType())->toBe('iDIN');
});

it('can be created', function () {
    $idin = new Idin;
    expect($idin)->toBeObject();
});

it('casts to array correctly', function () {
    $idin = new Idin;
    expect($idin->toArray())->toEqual([
        'Type' => 'iDIN',
    ]);
});
