<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications\Phonenumber;

it('returns the correct type', function () {
    $phonenumber = new Phonenumber('+31612345678');
    expect($phonenumber->getType())->toBe('PhoneNumber');
});

it('can be created with a valid phone number', function () {
    $phonenumber = new Phonenumber('+31612345678');
    expect($phonenumber->getPhoneNumber())->toBe('+31612345678')
        ->and($phonenumber->getSecureDownload())->toBeFalse();
});

it('can be created with secure downloads', function () {
    $phonenumber = new Phonenumber('+31612345678', true);
    expect($phonenumber->getPhoneNumber())->toBe('+31612345678')
        ->and($phonenumber->getSecureDownload())->toBeTrue();
});

it('normalizes phone numbers with spaces', function () {
    $phonenumber = new Phonenumber('+31 6 12 34 56 78');
    expect($phonenumber->getPhoneNumber())->toBe('+31612345678');
});

it('fails on incorrect phone number format', function () {
    new Phonenumber('+32612345678901234567890');
})->throws(SignhostException::class, 'Phone number has incorrect E.164 format.');

it('casts to array correctly', function () {
    $phonenumber = new Phonenumber('+31612345678');
    expect($phonenumber->toArray())->toEqual([
        'Type' => 'PhoneNumber',
        'Number' => '+31612345678',
        'SecureDownload' => false,
    ]);
});
