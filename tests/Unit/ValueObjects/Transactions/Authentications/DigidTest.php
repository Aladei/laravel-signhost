<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications\Digid;

it('returns the correct type', function () {
    $digid = new Digid('111222333');
    expect($digid->getType())->toBe('DigiD');
});

it('can be created with a valid BSN', function () {
    $digid = new Digid('111222333');
    expect($digid->getBsn())->toBe('111222333')
        ->and($digid->getSecureDownload())->toBeFalse();
});

it('can be created with secure download', function () {
    $digid = new Digid('111222333', true);
    expect($digid->getSecureDownload())->toBeTrue();
});

it('elfproef succeeds with leading zeros', function () {
    $digid = new Digid('1472495'); // Actually 001472495
    expect($digid->getBsn())->toBe('001472495');
});

it('fails when BSN contains non-digits or wrong length', function () {
    new Digid('abcdefghi');
})->throws(SignhostException::class, 'BSN must be 9 digits long.');

it('fails on elfproef check', function () {
    new Digid('111222334');
})->throws(SignhostException::class, 'Elfproef on BSN failed.');

it('casts to array correctly', function () {
    $digid = new Digid(111222333);
    expect($digid->toArray())->toEqual([
        'Type' => 'DigiD',
        'Bsn' => '111222333',
        'SecureDownload' => false,
    ]);
});
