<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Eherkenning
 */
class Eherkenning extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eherkenning implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  int|null  $entityConcernIdKvkNr
     * @param  string|null  $uid
     *
     * @throws SignhostException
     */
    public function __construct(
        protected ?int $entityConcernIdKvkNr = null,
        protected ?string $uid = null
    ) {
        parent::__construct($this->entityConcernIdKvkNr);
    }

    /**
     * @return string|null
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }
}
