<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\FileEntryLinkRel;
use Noardcode\LaravelSignhost\Tests\TestCase;

class FileEntryLinkRelTest extends TestCase
{
    public function test_values_and_from(): void
    {
        $this->assertSame('file', FileEntryLinkRel::File->value);
        $this->assertSame('receipt', FileEntryLinkRel::Receipt->value);
        $this->assertSame('signer.sign', FileEntryLinkRel::SignerSign->value);
        $this->assertSame('signer.download', FileEntryLinkRel::SignerDownload->value);

        $this->assertSame(FileEntryLinkRel::File, FileEntryLinkRel::from('file'));
        $this->assertSame(FileEntryLinkRel::SignerDownload, FileEntryLinkRel::from('signer.download'));
    }
}
