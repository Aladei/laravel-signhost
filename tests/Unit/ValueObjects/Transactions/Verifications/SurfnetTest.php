<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Surfnet;

it('returns the correct type', function () {
    $surfnet = new Surfnet;
    expect($surfnet->getType())->toBe('SURFnet');
});

it('can be created', function () {
    $surfnet = new Surfnet;
    expect($surfnet)->toBeObject();
});

it('casts to array correctly', function () {
    $surfnet = new Surfnet;
    expect($surfnet->toArray())->toEqual([
        'Type' => 'SURFnet',
    ]);
});
