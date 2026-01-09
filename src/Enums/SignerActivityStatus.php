<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum SignerActivityStatus
 *
 * Represents the lifecycle events/status codes emitted for a signer
 * during a transaction.
 *
 * @enum SignerActivityStatus
 */
enum SignerActivityStatus: int
{
    case InvitationSent = 101;
    case Received = 102;
    case Opened = 103;
    case ReminderSent = 104;
    case DocumentOpened = 105;
    case Cancelled = 201;
    case Rejected = 202;
    case Signed = 203;
    case SignedDocumentSent = 301;
    case SignedDocumentOpened = 302;
    case SignedDocumentDownloaded = 303;
    case ReceiptSent = 401;
    case ReceiptOpened = 402;
    case ReceiptDownloaded = 403;
    case Finished = 500;
    case Deleted = 600;
    case Expired = 700;
    case Failed = 999;

    public function label(): string
    {
        return match ($this) {
            self::InvitationSent => 'invitation_sent',
            self::Received => 'received',
            self::Opened => 'opened',
            self::ReminderSent => 'reminder_sent',
            self::DocumentOpened => 'document_opened',
            self::Cancelled => 'cancelled',
            self::Rejected => 'rejected',
            self::Signed => 'signed',
            self::SignedDocumentSent => 'signed_document_sent',
            self::SignedDocumentOpened => 'signed_document_opened',
            self::SignedDocumentDownloaded => 'signed_document_downloaded',
            self::ReceiptSent => 'receipt_sent',
            self::ReceiptOpened => 'receipt_opened',
            self::ReceiptDownloaded => 'receipt_downloaded',
            self::Finished => 'finished',
            self::Deleted => 'deleted',
            self::Expired => 'expired',
            self::Failed => 'failed',
        };
    }
}
