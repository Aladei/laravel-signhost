<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eidaslogin;

it('returns the correct type', function () {
    $eidaslogin = new Eidaslogin;
    expect($eidaslogin->getType())->toBe('eIDAS Login');
});

it('can be created', function () {
    $eidaslogin = new Eidaslogin;
    expect($eidaslogin)->toBeObject();
});

it('casts to array correctly', function () {
    $eidasLogin = new Eidaslogin;
    expect($eidasLogin->toArray())->toEqual([
        'Type' => 'eIDAS Login',
    ]);
});
