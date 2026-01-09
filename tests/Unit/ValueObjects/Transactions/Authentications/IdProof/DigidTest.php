<?php

it('returns the correct type', function () {
    $digid = new \Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications\IdProof\Digid(
        '111222333',
        true,
        \Noardcode\LaravelSignhost\Enums\ReliabilityLevel::High
    );
    expect($digid->getType())->toBe('DigiD')
        ->and($digid->getReliabilityLevel())->toBe(\Noardcode\LaravelSignhost\Enums\ReliabilityLevel::High);
});
