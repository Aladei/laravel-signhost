<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Eidaslogin
 *
 */
class Eidaslogin implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'eIDAS Login';
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
        ];
    }
}
