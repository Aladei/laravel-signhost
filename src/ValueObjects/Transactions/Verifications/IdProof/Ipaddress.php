<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Ipaddress
 */
class Ipaddress extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ipaddress implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $ipAddress
     */
    public function __construct(
        protected ?string $ipAddress = null,
    ) {
        //
    }

    /**
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }
}
