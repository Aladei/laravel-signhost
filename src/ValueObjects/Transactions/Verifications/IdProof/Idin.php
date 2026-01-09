<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Idin
 */
class Idin extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Idin implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $accountHolderName
     * @param  string|null  $accountHolderAddress1
     * @param  string|null  $accountHolderAddress2
     * @param  string|null  $accountHolderDateOfBirth
     * @param  array|null  $attributes
     */
    public function __construct(
        protected ?string $accountHolderName = null,
        protected ?string $accountHolderAddress1 = null,
        protected ?string $accountHolderAddress2 = null,
        protected ?string $accountHolderDateOfBirth = null,
        protected ?array $attributes = []
    ) {
        //
    }

    /**
     * @return string|null
     */
    public function getAccountHolderName(): ?string
    {
        return $this->accountHolderName;
    }

    /**
     * @return string|null
     */
    public function getAccountHolderAddress1(): ?string
    {
        return $this->accountHolderAddress1;
    }

    /**
     * @return string|null
     */
    public function getAccountHolderAddress2(): ?string
    {
        return $this->accountHolderAddress2;
    }

    /**
     * @return string|null
     */
    public function getAccountHolderDateOfBirth(): ?string
    {
        return $this->accountHolderDateOfBirth;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }
}
