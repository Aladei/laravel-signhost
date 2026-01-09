<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Itsmesign
 */
class Itsmesign implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'itsme sign';
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'Type' => 'itsme sign',
        ];
    }
}
