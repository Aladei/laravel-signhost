<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Digid
 */
class Digid implements ToSignhostArrayContract, VerificationContract
{
    protected string $bsn;

    /**
     * @param  string|null  $bsn
     *
     * @throws SignhostException
     */
    public function __construct(?string $bsn = null)
    {
        if (! empty($bsn)) {
            $this->bsn = $bsn;
        }

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
        if (! isset($this->bsn)) {
            return;
        }

        // Check the length of the BSN.
        $this->bsn = str_pad($this->bsn, 9, '0', STR_PAD_LEFT);
        if ($this->bsn && ! preg_match('/^\d{9}$/', $this->bsn)) {
            throw new SignhostException('BSN must be 9 digits long.');
        }

        // Check if the BSN is valid with elfproef.
        if (! $this->isValidElfproef()) {
            throw new SignhostException('Elfproef on BSN failed.');
        }
    }

    /**
     * Check if BSN is valid with elfproef.
     *
     * @return bool
     */
    protected function isValidElfproef(): bool
    {
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += (int) $this->bsn[$i] * (9 - $i);
        }

        // Last number is counted as -1 in stead of 1.
        $sum -= (int) $this->bsn[8];

        // Check if the sum is divisible by 11.
        return $sum % 11 === 0;
    }

    /**
     * @return string|null
     */
    public function getBsn(): ?string
    {
        return $this->bsn;
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
        ];
    }
}
