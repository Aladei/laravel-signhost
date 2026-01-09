<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ideal;

it('returns the correct type', function () {
    $ideal = new Ideal('NL98INGB0003856625');
    expect($ideal->getType())->toBe('iDeal');
});

it('can be created with a valid IBAN', function () {
    $ideal = new Ideal('NL98INGB0003856625');
    expect($ideal)->toBeObject()
        ->and($ideal->getIban())->toBe('NL98INGB0003856625');
});

it('normalizes IBAN with spaces', function () {
    $ideal = new Ideal('NL98 INGB 0003 8566 25');
    expect($ideal)->toBeObject()
        ->and($ideal->getIban())->toBe('NL98INGB0003856625');
});

it('can be created without IBAN', function () {
    $ideal = new Ideal;
    expect($ideal)->toBeObject();
});

it('fails on incorrect IBAN format', function () {
    new Ideal('NLBE98INGB0003856625');
})->throws(SignhostException::class, 'IBAN has incorrect format.');

it('fails on IBAN checksum', function () {
    new Ideal('NL98FAIL0003856625');
})->throws(SignhostException::class, 'IBAN checksum test failed.');

it('casts to array correctly', function () {
    $ideal = new Ideal('NL98INGB0003856625');
    expect($ideal->toArray())->toEqual([
        'Type' => 'iDeal',
        'Iban' => 'NL98INGB0003856625',
    ]);
});
