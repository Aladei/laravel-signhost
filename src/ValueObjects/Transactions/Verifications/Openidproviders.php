<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Openidproviders
 */
class Openidproviders implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string|null  $providerName
     */
    public function __construct(
        protected ?string $providerName = null
    ) {
        //
    }

    /**
     * @return string|null
     */
    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'OpenID Providers';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'ProviderName' => $this->getProviderName(),
        ];
    }
}
