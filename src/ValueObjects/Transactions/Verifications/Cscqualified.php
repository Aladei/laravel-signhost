<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Cscqualified
 */
class Cscqualified implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'CSC Qualified';
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
