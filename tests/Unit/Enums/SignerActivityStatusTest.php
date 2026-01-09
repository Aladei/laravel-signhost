<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\SignerActivityStatus;
use Noardcode\LaravelSignhost\Tests\TestCase;

class SignerActivityStatusTest extends TestCase
{
    public function test_values_and_labels(): void
    {
        $this->assertSame(101, SignerActivityStatus::InvitationSent->value);
        $this->assertSame(102, SignerActivityStatus::Received->value);
        $this->assertSame(103, SignerActivityStatus::Opened->value);
        $this->assertSame(105, SignerActivityStatus::DocumentOpened->value);
        $this->assertSame(201, SignerActivityStatus::Cancelled->value);
        $this->assertSame(202, SignerActivityStatus::Rejected->value);
        $this->assertSame(203, SignerActivityStatus::Signed->value);
        $this->assertSame(303, SignerActivityStatus::SignedDocumentDownloaded->value);
        $this->assertSame(403, SignerActivityStatus::ReceiptDownloaded->value);
        $this->assertSame(500, SignerActivityStatus::Finished->value);
        $this->assertSame(600, SignerActivityStatus::Deleted->value);
        $this->assertSame(700, SignerActivityStatus::Expired->value);
        $this->assertSame(999, SignerActivityStatus::Failed->value);

        $this->assertSame('invitation_sent', SignerActivityStatus::InvitationSent->label());
        $this->assertSame('received', SignerActivityStatus::Received->label());
        $this->assertSame('opened', SignerActivityStatus::Opened->label());
        $this->assertSame('document_opened', SignerActivityStatus::DocumentOpened->label());
        $this->assertSame('cancelled', SignerActivityStatus::Cancelled->label());
        $this->assertSame('rejected', SignerActivityStatus::Rejected->label());
        $this->assertSame('signed', SignerActivityStatus::Signed->label());
        $this->assertSame('signed_document_downloaded', SignerActivityStatus::SignedDocumentDownloaded->label());
        $this->assertSame('receipt_downloaded', SignerActivityStatus::ReceiptDownloaded->label());
        $this->assertSame('finished', SignerActivityStatus::Finished->label());
        $this->assertSame('deleted', SignerActivityStatus::Deleted->label());
        $this->assertSame('expired', SignerActivityStatus::Expired->label());
        $this->assertSame('failed', SignerActivityStatus::Failed->label());
    }
}
