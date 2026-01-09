<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions;

use Carbon\Carbon;
use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Enums\Language;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Receiver
 */
class Receiver implements ToSignhostArrayContract
{
    /**
     * @param  string  $name  The name of the receiver.
     * @param  string  $email  The e-mail address of the receiver.
     * @param  Language  $language  The language of the receiving.
     * @param  string|null  $subject  The subject of the email in plain text (max. 64 characters).
     * @param  string|null  $message  The email message towards to the receiver in plain text.
     * @param  string|null  $reference  The reference of the signer.
     * @param  string|null  $context  Any valid JSON object that we will return back to you when doing a GET on the transaction or when we send a postback.
     *
     * @throws SignhostException
     */
    public function __construct(
        protected string $name,
        protected string $email,
        protected Language $language,
        protected ?string $subject = null,
        protected ?string $message = null,
        protected ?string $reference = null,
        protected ?string $context = null,
        protected ?string $id = null,
        protected ?Carbon $createdDateTime = null,
        protected ?Carbon $modifiedDateTime = null,
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
        $this->validateSubject();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    protected function validateSubject(): void
    {
        if (empty($this->subject)) {
            return;
        }

        // Check if the subject is not longer than 64 characters.
        if (strlen($this->subject) > 64) {
            throw new SignhostException('The subject is too long. Max 64 characters.');
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return string|null
     */
    public function getContext(): ?string
    {
        return $this->context;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedDateTime(): ?Carbon
    {
        return $this->createdDateTime;
    }

    /**
     * @return Carbon|null
     */
    public function getModifiedDateTime(): ?Carbon
    {
        return $this->modifiedDateTime;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'Name' => $this->getName(),
            'Email' => $this->getEmail(),
            'Language' => $this->getLanguage()->value,
            'Subject' => $this->getSubject(),
            'Message' => $this->getMessage(),
            'Reference' => $this->getReference(),
            'Context' => $this->getContext(),
        ], fn ($value) => $value !== null && $value !== '');
    }
}
