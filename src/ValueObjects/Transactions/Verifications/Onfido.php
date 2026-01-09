<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;

/**
 * Class Onfido
 */
class Onfido implements ToSignhostArrayContract, VerificationContract
{
    /**
     * @param  $workflowId  string|null
     *
     * @throws SignhostException
     */
    public function __construct(
        protected ?string $workflowId = null
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
        $this->validateWorkflowId();
    }

    /**
     * @return void
     *
     * @throws SignhostException
     */
    protected function validateWorkflowId(): void
    {
        if (is_null($this->workflowId)) {
            return;
        }

        // Check if the workflow ID is a valid UUID.
        if ($this->workflowId && ! preg_match('/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/', $this->workflowId)) {
            throw new SignhostException('Workflow Id is not a valid UUID.');
        }
    }

    /**
     * @return string|null
     */
    public function getWorkflowId(): ?string
    {
        return $this->workflowId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Onfido';
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'Type' => $this->getType(),
            'WorkflowId' => $this->getWorkflowId(),
        ];
    }
}
