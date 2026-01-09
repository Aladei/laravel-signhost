<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Ideal
 */
class Ideal implements ToSignhostArrayContract, VerificationContract
{
    protected string $iban;

    /**
     * @param  string|null  $iban
     *
     * @throws SignhostException
     */
    public function __construct(
        ?string $iban = null
    ) {
        if (! empty($iban)) {
            $this->iban = $iban;
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
        $this->validateIban();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    protected function validateIban(): void
    {
        if (! isset($this->iban)) {
            return;
        }

        // Set the IBAN to uppercase and remove spaces and dashes.
        $this->iban = strtoupper(str_replace([' ', '-'], '', $this->iban));

        // Check the length of the BSN.
        if ($this->iban && ! preg_match('/^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/', $this->iban)) {
            throw new SignhostException('IBAN has incorrect format.');
        }

        // Check if the BSN is valid with elfproef.
        if (! $this->isValidIban()) {
            throw new SignhostException('IBAN checksum test failed.');
        }
    }

    /**
     * IBAN checksum test based on ISO 13616.
     *
     * 1. Move the first four characters to the end of the string.
     * 2. Replace each letter in the string with two digits, A=10, B=11, ..., Z=35.
     * 3. Interpret the string as a decimal integer and compute the remainder of that number on division by 97.
     *
     * @return bool
     */
    protected function isValidIban(): bool
    {
        $rearrangedIban = substr($this->iban, 4).substr($this->iban, 0, 4);

        $convertedIban = '';
        foreach (str_split($rearrangedIban) as $char) {
            if (ctype_alpha($char)) {
                $convertedIban .= ord($char) - 55; // A=10, B=11, ..., Z=35
            } else {
                $convertedIban .= $char;
            }
        }

        return bcmod($convertedIban, '97') == 1;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'iDeal';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'Iban' => $this->getIban(),
        ];
    }
}
