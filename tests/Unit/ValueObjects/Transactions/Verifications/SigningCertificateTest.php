<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\SigningCertificate;

it('returns the correct type', function () {
    $signingcertificate = new SigningCertificate;
    expect($signingcertificate->getType())->toBe('SigningCertificate');
});

it('can be created', function () {
    $signingcertificate = new SigningCertificate;
    expect($signingcertificate)->toBeObject();
});

it('casts to array correctly', function () {
    $signingcertificate = new SigningCertificate;
    expect($signingcertificate->toArray())->toEqual([
        'Type' => 'SigningCertificate',
    ]);
});
