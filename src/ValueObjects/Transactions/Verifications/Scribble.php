<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Scribble
 */
class Scribble implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  bool  $requireHandsignature
     * @param  bool  $scribbleNameFixed
     * @param  string|null  $scribbleName
     *
     * @throws SignhostException
     */
    public function __construct(
        protected bool $requireHandsignature = false, // When true, signer is required to draw a hand signature.
        protected bool $scribbleNameFixed = false, // When true, the signer will not be able to change its scribble name.
        protected ?string $scribbleName = null // Will be pre-filled in the scribble form, required when scribbleNameFixed is true.
    ) {
        $this->validate();
    }

    /**
     * Validate the Scribble object.
     *
     * @throws SignhostException
     */
    protected function validate(): void
    {
        $this->validateScribbleName();
    }

    /**
     * Validate the scribble name.
     *
     * @throws SignhostException
     */
    protected function validateScribbleName(): void
    {
        if ($this->scribbleNameFixed && (is_null($this->scribbleName) || empty($this->scribbleName))) {
            throw new SignhostException('Scribble name is required when scribbleNameFixed is true.');
        }
    }

    /**
     * @return bool
     */
    public function getRequireHandsignature(): bool
    {
        return $this->requireHandsignature;
    }

    /**
     * @return bool
     */
    public function getScribbleNameFixed(): bool
    {
        return $this->scribbleNameFixed;
    }

    /**
     * @return string|null
     */
    public function getScribbleName(): ?string
    {
        return $this->scribbleName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Scribble';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'RequireHandsignature' => $this->getRequireHandsignature(),
            'ScribbleNameFixed' => $this->getScribbleNameFixed(),
            'ScribbleName' => $this->getScribbleName(),
        ];
    }
}
