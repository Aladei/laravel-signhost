<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Digid
 */
class Eidaslogin extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eidaslogin implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $uid
     * @param  string|null  $level
     * @param  string|null  $firstName
     * @param  string|null  $lastName
     * @param  string|null  $dateOfBirth
     * @param  array|null  $attributes
     */
    public function __construct(
        protected ?string $uid = null,
        protected ?string $level = null,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?string $dateOfBirth = null, // Y-m-d
        protected ?array $attributes = [],
    ) {
        //
    }

    /**
     * @return string|null
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @return string|null
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }
}
