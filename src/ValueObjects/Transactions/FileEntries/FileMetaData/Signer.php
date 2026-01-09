<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;

/**
 * Class Signer
 */
class Signer implements ToSignhostArrayContract
{
    /**
     * @param  string  $id
     * @param  array  $formSets
     */
    public function __construct(
        protected string $id,
        protected array $formSets,
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFormSets(): array
    {
        return $this->formSets;
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return [
            'Id' => $this->getId(),
            'FormSets' => $this->getFormSets(),
        ];
    }
}
