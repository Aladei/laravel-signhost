<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Email
 */
class Email implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  string  $email
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $email
    ) {
        $this->validate();
    }

    /**
     * Validate the email address.
     *
     * @throws SignhostException
     */
    protected function validate(): void
    {
        $this->validateEmail();
    }

    /**
     * Validate the email address format.
     *
     * @throws SignhostException
     */
    protected function validateEmail(): void
    {
        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new SignhostException('Invalid email address format.');
        }
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Email';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'Email' => $this->getEmail(),
        ];
    }
}
