<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Cscqualified
 */
class Cscqualified extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Cscqualified implements ToSignhostArrayContract, VerificationContract
{
    public function __construct(
        protected ?string $issuer = null,
        protected ?string $subject = null,
        protected ?string $thumbprint = null,
        protected ?string $provider = null
    ) {
        //
    }

    /**
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return string|null
     */
    public function getThumbprint(): ?string
    {
        return $this->thumbprint;
    }

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }
}
