<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum TransactionStatus
 *
 * Reflects the overall status of a Signhost transaction.
 *
 * @enum TransactionStatus
 */
enum TransactionStatus: int
{
    case WaitingForDocument = 5;
    case WaitingForSigner = 10;
    case InProgress = 20;
    case Signed = 30;
    case Rejected = 40;
    case Expired = 50;
    case Cancelled = 60;
    case Failed = 70;

    public function label(): string
    {
        return match ($this) {
            self::WaitingForDocument => 'waiting_for_document',
            self::WaitingForSigner => 'waiting_for_signer',
            self::InProgress => 'in_progress',
            self::Signed => 'signed',
            self::Rejected => 'rejected',
            self::Expired => 'expired',
            self::Cancelled => 'cancelled',
            self::Failed => 'failed',
        };
    }
}
