<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications;

use Noardcode\LaravelSignhost\Contracts\AuthenticationContract;
use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Digid
 */
class Digid implements AuthenticationContract, ToSignhostArrayContract
{
    /**
     * @param  string  $bsn
     * @param  bool  $secureDownload
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $bsn,
        protected bool $secureDownload = false
    ) {
        $this->validate();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    protected function validate(): void
    {
        $this->validateBsn();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    protected function validateBsn(): void
    {
        $this->bsn = str_pad($this->bsn, 9, '0', STR_PAD_LEFT); // Check the length of the BSN.
        if ($this->bsn && ! preg_match('/^\d{9}$/', $this->bsn)) {
            throw new SignhostException('BSN must be 9 digits long.');
        }

        // Check if the BSN is valid with elfproef.
        if (! $this->isValidElfproef()) {
            throw new SignhostException('Elfproef on BSN failed.');
        }
    }

    /**
     * @return bool
     */
    protected function isValidElfproef(): bool
    {
        $som = 0;
        for ($i = 0; $i < 8; $i++) {
            $som += (int) $this->bsn[$i] * (9 - $i);
        }

        // The last number is counted as -1 instead of 1.
        $som -= (int) $this->bsn[8];

        // Check if the sum is divisible by 11.
        return $som % 11 === 0;
    }

    /**
     * @return string
     */
    public function getBsn(): string
    {
        return $this->bsn;
    }

    /**
     * @return bool
     */
    public function getSecureDownload(): bool
    {
        return $this->secureDownload;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'DigiD';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'Bsn' => $this->getBsn(),
            'SecureDownload' => $this->getSecureDownload(),
        ];
    }
}
