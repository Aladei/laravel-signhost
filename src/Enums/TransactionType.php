<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum TransactionType
 *
 * Indicates the functional category of a transaction handled by the
 * package (e.g., document signing or identity proofing).
 *
 * @enum TransactionType
 */
enum TransactionType: string
{
    case DocumentSign = 'document_sign';
    case IdProof = 'id_proof';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            TransactionType::DocumentSign => 'Document Sign',
            TransactionType::IdProof => 'ID Proof',
            TransactionType::Unknown => 'Unknown',
        };
    }
}
