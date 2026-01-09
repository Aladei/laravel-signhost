<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions;

use Carbon\Carbon;
use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Enums\SignerActivityStatus;

class Activity implements ToSignhostArrayContract
{
    public function __construct(
        protected string $id,
        protected SignerActivityStatus $code,
        protected string $activity,
        protected Carbon $createdDateTime,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): SignerActivityStatus
    {
        return $this->code;
    }

    public function getActivity(): string
    {
        return $this->activity;
    }

    public function getCreatedDateTime(): Carbon
    {
        return $this->createdDateTime;
    }

    public function toArray(): array
    {
        return [
            'Id' => $this->getId(),
            'Code' => $this->getCode()->value,
            'Activity' => $this->getActivity(),
            'CreatedDateTime' => $this->getCreatedDateTime()->toIso8601String(),
        ];
    }
}
