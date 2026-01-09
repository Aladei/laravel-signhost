<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class SigningCertificate
 */
class SigningCertificate extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\SigningCertificate implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $issuer
     * @param  string|null  $subject
     * @param  string|null  $thumbprint
     */
    public function __construct(
        protected ?string $issuer,
        protected ?string $subject,
        protected ?string $thumbprint
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
}
