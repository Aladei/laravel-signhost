<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eherkenning;

it('returns the correct type', function () {
    $eherkenning = new Eherkenning(11122233);
    expect($eherkenning->getType())->toBe('eHerkenning');
});

it('can be created with valid kvk number', function () {
    $eherkenning = new Eherkenning(11122233);
    expect($eherkenning)->toBeObject()
        ->and($eherkenning->getEntityConcernIdKvkNr())->toBe(11122233);
});

it('can be created without kvk number', function () {
    $eherkenning = new Eherkenning;
    expect($eherkenning)->toBeObject();
});

it('throws on too long kvk number', function () {
    new Eherkenning(111222333);
})->throws(SignhostException::class);

it('casts to array correctly', function () {
    $eherkenning = new Eherkenning(11122233);
    expect($eherkenning->toArray())->toEqual([
        'Type' => 'eHerkenning',
        'EntityConcernIdKvkNr' => '11122233',
    ]);
});
