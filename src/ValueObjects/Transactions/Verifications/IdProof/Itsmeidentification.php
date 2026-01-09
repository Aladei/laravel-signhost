<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Itsmeidentification
 */
class Itsmeidentification extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmeidentification implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string  $phoneNumber
     * @param  array|null  $attributes
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $phoneNumber,
        protected ?array $attributes = [],
    ) {
        parent::__construct($this->phoneNumber);
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }
}
