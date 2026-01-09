<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Surfnet
 *
 */
class Surfnet implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'SURFnet';
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'Type' => 'SURFnet',
        ];
    }
}
