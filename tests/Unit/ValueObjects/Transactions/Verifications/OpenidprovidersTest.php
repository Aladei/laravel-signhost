<?php

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Openidproviders;

it('returns the provider name', function () {
    $openidproviders = new Openidproviders('Entrust IDaaS');
    expect($openidproviders->getProviderName())->toBe('Entrust IDaaS');
});

it('can be created with provider name', function () {
    $openidproviders = new Openidproviders('Entrust IDaaS');
    expect($openidproviders)->toBeObject()
        ->and($openidproviders->getProviderName())->toBe('Entrust IDaaS');
});

it('can be created without provider name', function () {
    $openidproviders = new Openidproviders;
    expect($openidproviders)->toBeObject()
        ->and($openidproviders->getProviderName())->toBeNull();
});

it('casts to array correctly', function () {
    $openidproviders = new Openidproviders('Entrust IDaaS');
    expect($openidproviders->toArray())->toEqual([
        'Type' => 'OpenID Providers',
        'ProviderName' => 'Entrust IDaaS',
    ]);
});
