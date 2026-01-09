<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications\IdProof;

use Noardcode\LaravelSignhost\Contracts\AuthenticationContract;
use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Enums\ReliabilityLevel;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Digid
 */
class Digid extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications\Digid implements AuthenticationContract, ToSignhostArrayContract
{
    /**
     * @param  string  $bsn
     * @param  bool  $secureDownload
     * @param  ReliabilityLevel|null  $reliabilityLevel
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $bsn,
        protected bool $secureDownload = false,
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
