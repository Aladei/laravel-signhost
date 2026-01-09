<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData\FormSet;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Enums\FormSetType;

/**
 * Class FormSet
 */
class FieldType implements ToSignhostArrayContract
{
    /**
     * @param  FormSetType  $formSetType  Field type to create.
     * @param  Location  $location  Specify where the field should be placed within the document.
     */
    public function __construct(
        protected string $name,
        protected FormSetType $formSetType,
        protected Location $location,
    ) {
        $this->validate();
    }

    /**
     * @return void
     */
    private function validate(): void
    {
        $this->validateName();
    }

    /**
     * @return void
     */
    private function validateName(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('FormSet name cannot be empty');
        }

        // Check if name only contains a-z, A-Z, 0-9 and _.
        if (! preg_match('/^[a-zA-Z0-9_]+$/', $this->name)) {
            throw new \InvalidArgumentException('FormSet name can only contain a-z A-Z 0-9 _');
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
     * @return FormSetType
     */
    public function getFormSetType(): FormSetType
    {
        return $this->formSetType;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getFormSetType()->value,
            'Location' => $this->getLocation()->toArray(),
        ];
    }
}
