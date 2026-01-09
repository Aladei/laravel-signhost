<?php

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Scribble;

it('returns the correct type', function () {
    $scribble = new Scribble(false, false, 'NoardCode');
    expect($scribble->getType())->toBe('Scribble');
});

it('can be created with require handsignature', function () {
    $scribble = new Scribble(true);
    expect($scribble)->toBeObject()
        ->and($scribble->getRequireHandsignature())->toBeTrue()
        ->and($scribble->getScribbleNameFixed())->toBeFalse()
        ->and($scribble->getScribbleName())->toBeNull();
});

it('can be created with name fixed', function () {
    $scribble = new Scribble(false, true, 'NoardCode');
    expect($scribble)->toBeObject()
        ->and($scribble->getRequireHandsignature())->toBeFalse()
        ->and($scribble->getScribbleNameFixed())->toBeTrue()
        ->and($scribble->getScribbleName())->toBe('NoardCode');
});

it('cannot be created with name fixed but without name', function () {
    new Scribble(false, true);
})->throws(SignhostException::class, 'Scribble name is required when scribbleNameFixed is true.');

it('casts to array correctly', function () {
    $scribble = new Scribble(false, false, 'NoardCode');
    expect($scribble->toArray())->toEqual([
        'Type' => 'Scribble',
        'RequireHandsignature' => false,
        'ScribbleNameFixed' => false,
        'ScribbleName' => 'NoardCode',
    ]);
});
