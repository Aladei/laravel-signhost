<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Itsmesign
 */
class Itsmesign extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmesign implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string  $issuer
     * @param  string  $subject
     * @param  string  $thumbprint
     */
    public function __construct(
        protected string $issuer,
        protected string $subject,
        protected string $thumbprint
    ) {
        //
    }

    /**
     * @return string
     */
    public function getIssuer(): string
    {
        return $this->issuer;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getThumbprint(): string
    {
        return $this->thumbprint;
    }
}
