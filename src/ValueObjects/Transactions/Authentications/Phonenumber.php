<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Authentications;

use Noardcode\LaravelSignhost\Contracts\AuthenticationContract;
use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Phonenumber
 */
class Phonenumber implements AuthenticationContract, ToSignhostArrayContract
{
    /**
     * @param  string  $phoneNumber
     * @param  bool  $secureDownload
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $phoneNumber,
        protected bool $secureDownload = false
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
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
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
        return 'PhoneNumber';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'Number' => $this->getPhoneNumber(),
            'SecureDownload' => $this->getSecureDownload(),
        ];
    }
}
