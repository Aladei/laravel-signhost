<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Digid
 */
class Ideal extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ideal implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $iban
     * @param  string|null  $accountHolderName
     * @param  string|null  $accountHolderCity
     *
     * @throws SignhostException
     */
    public function __construct(
        ?string $iban = null,
        protected ?string $accountHolderName = null,
        protected ?string $accountHolderCity = null
    ) {
        parent::__construct($iban);
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
    public function getAccountHolderCity(): ?string
    {
        return $this->accountHolderCity;
    }
}
