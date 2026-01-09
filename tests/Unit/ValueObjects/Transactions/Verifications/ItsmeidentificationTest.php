<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmeidentification;

it('returns the correct type', function () {
    $itsmeidentification = new Itsmeidentification('+32612345678');
    expect($itsmeidentification->getType())->toBe('itsme Identification');
});

it('can be created with a valid phone number', function () {
    $itsmeidentification = new Itsmeidentification('+32612345678');
    expect($itsmeidentification)->toBeObject()
        ->and($itsmeidentification->getPhoneNumber())->toBe('+32612345678');
});

it('normalizes phone numbers with spaces', function () {
    $itsmeidentification = new Itsmeidentification('+32 6 12 34 56 78');
    expect($itsmeidentification)->toBeObject()
        ->and($itsmeidentification->getPhoneNumber())->toBe('+32612345678');
});

it('fails on incorrect phone number format', function () {
    new Itsmeidentification('+32612345678901234567890');
})->throws(SignhostException::class, 'Phone number has incorrect E.164 format.');

it('fails on non-Belgian phone number', function () {
    new Itsmeidentification('+31612345678');
})->throws(SignhostException::class, 'Phone number must have +32 country calling code.');

it('casts to array correctly', function () {
    $consent = new Itsmeidentification('+32612345678');
    expect($consent->toArray())->toEqual([
        'Type' => 'itsme Identification',
        'PhoneNumber' => '+32612345678',
    ]);
});
