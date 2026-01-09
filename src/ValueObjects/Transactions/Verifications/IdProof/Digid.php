<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Enums\ReliabilityLevel;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Digid
 */
class Digid extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Digid implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $bsn
     * @param  ReliabilityLevel|null  $reliabilityLevel
     *
     * @throws SignhostException
     */
    public function __construct(
        ?string $bsn = null,
        protected ?ReliabilityLevel $reliabilityLevel = null
    ) {
        parent::__construct($bsn);
    }

    /**
     * @return ReliabilityLevel|null
     */
    public function getReliabilityLevel(): ?ReliabilityLevel
    {
        return $this->reliabilityLevel;
    }
}
