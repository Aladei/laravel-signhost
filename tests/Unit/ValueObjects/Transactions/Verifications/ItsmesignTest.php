<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmesign;

it('returns the correct type', function () {
    $itsmesign = new Itsmesign;
    expect($itsmesign->getType())->toBe('itsme sign');
});

it('can be created', function () {
    $itsmesign = new Itsmesign;
    expect($itsmesign)->toBeObject();
});

it('casts to array correctly', function () {
    $itsmesign = new Itsmesign;
    expect($itsmesign->toArray())->toEqual([
        'Type' => 'itsme sign',
    ]);
});
