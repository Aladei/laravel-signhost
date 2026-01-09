<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum FileEntryLinkRel
 *
 * Denotes the relationship type for links provided in transaction
 * file entries (e.g., file download, receipt, signer actions).
 *
 * @enum FileEntryLinkRel
 */
enum FileEntryLinkRel: string
{
    case File = 'file';
    case Receipt = 'receipt';
    case SignerSign = 'signer.sign';
    case SignerDownload = 'signer.download';
}
