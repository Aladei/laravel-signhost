<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Idin
 */
class Idin implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'iDIN';
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
