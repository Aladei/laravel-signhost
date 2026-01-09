<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ipaddress;

it('returns the correct type', function () {
    $ipaddress = new Ipaddress;
    expect($ipaddress->getType())->toBe('IPAddress');
});

it('can be created', function () {
    $ipaddress = new Ipaddress;
    expect($ipaddress)->toBeObject();
});

it('casts to array correctly', function () {
    $ipaddress = new Ipaddress;
    expect($ipaddress->toArray())->toEqual([
        'Type' => 'IPAddress',
    ]);
});
