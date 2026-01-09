<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Eherkenning
 */
class Eherkenning implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  int|null  $entityConcernIdKvkNr
     *
     * @throws SignhostException
     */
    public function __construct(
        protected ?int $entityConcernIdKvkNr = null
    ) {
        $this->validate();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    public function validate(): void
    {
        $this->validateEntityConcernIdKvkNr();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    public function validateEntityConcernIdKvkNr(): void
    {
        if (is_null($this->entityConcernIdKvkNr)) {
            return;
        }

        // Check the length of the entityConcernIdKvkNr.
        if ($this->entityConcernIdKvkNr && ! preg_match('/^\d{8}$/', (string) $this->entityConcernIdKvkNr)) {
            throw new SignhostException('Entity concern ID KVK number must be 8 digits long.');
        }
    }

    /**
     * @return int|null
     */
    public function getEntityConcernIdKvkNr(): ?int
    {
        return $this->entityConcernIdKvkNr;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'eHerkenning';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'EntityConcernIdKvkNr' => $this->getEntityConcernIdKvkNr(),
        ];
    }
}
