<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\FileEntries\FileMetaData;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;

/**
 * Class FormSet
 */
class FormSet implements ToSignhostArrayContract
{
    /**
     * @param  string  $name
     * @param  array  $fieldTypes
     */
    public function __construct(
        protected string $name,
        protected array $fieldTypes,
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
     * @return array
     */
    public function getFieldTypes(): array
    {
        return $this->fieldTypes;
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        $fieldTypes = [];

        foreach ($this->getFieldTypes() as $fieldType) {
            $fieldTypes[$fieldType->getName()] = $fieldType->toArray();
        }

        return $fieldTypes;
    }
}
