<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Itsmeidentification
 */
class Itsmeidentification implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string  $phoneNumber
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $phoneNumber
    ) {
        $this->validate();
    }

    /**
     * Validate the phone number.
     *
     * @throws SignhostException
     */
    protected function validate(): void
    {
        $this->validatePhoneNumber();
    }

    /**
     * Validate the phone number format.
     *
     * @throws SignhostException
     */
    protected function validatePhoneNumber(): void
    {
        $this->phoneNumber = strtoupper(str_replace([' ', '-'], '', $this->phoneNumber));

        // Check the format of the phone number.
        if ($this->phoneNumber && ! preg_match('/^\+[1-9]\d{1,14}$/', $this->phoneNumber)) {
            throw new SignhostException('Phone number has incorrect E.164 format.');
        }

        // Check if country calling code is +32 (only Belgium is supported).
        if (substr($this->phoneNumber, 0, 3) !== '+32') {
            throw new SignhostException('Phone number must have +32 country calling code.');
        }
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'itsme Identification';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'PhoneNumber' => $this->getPhoneNumber(),
        ];
    }
}
