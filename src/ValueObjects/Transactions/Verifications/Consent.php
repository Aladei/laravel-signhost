<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Consent
 */
class Consent implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Consent';
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
