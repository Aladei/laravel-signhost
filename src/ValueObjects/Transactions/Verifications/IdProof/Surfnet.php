<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Surfnet
 */
class Surfnet extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Surfnet implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $uid
     * @param  array|null  $attributes
     */
    public function __construct(
        protected ?string $uid = null,
        protected ?array $attributes = []
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
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }
}
