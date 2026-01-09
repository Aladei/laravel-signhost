<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Tests\TestCase;

class TransactionStatusTest extends TestCase
{
    public function test_values_and_labels(): void
    {
        $this->assertSame(5, TransactionStatus::WaitingForDocument->value);
        $this->assertSame(10, TransactionStatus::WaitingForSigner->value);
        $this->assertSame(20, TransactionStatus::InProgress->value);
        $this->assertSame(30, TransactionStatus::Signed->value);
        $this->assertSame(40, TransactionStatus::Rejected->value);
        $this->assertSame(50, TransactionStatus::Expired->value);
        $this->assertSame(60, TransactionStatus::Cancelled->value);
        $this->assertSame(70, TransactionStatus::Failed->value);

        $this->assertSame('waiting_for_document', TransactionStatus::WaitingForDocument->label());
        $this->assertSame('waiting_for_signer', TransactionStatus::WaitingForSigner->label());
        $this->assertSame('in_progress', TransactionStatus::InProgress->label());
        $this->assertSame('signed', TransactionStatus::Signed->label());
        $this->assertSame('rejected', TransactionStatus::Rejected->label());
        $this->assertSame('expired', TransactionStatus::Expired->label());
        $this->assertSame('cancelled', TransactionStatus::Cancelled->label());
        $this->assertSame('failed', TransactionStatus::Failed->label());
    }

    public function test_from_returns_expected_case(): void
    {
        $this->assertSame(TransactionStatus::Signed, TransactionStatus::from(30));
    }
}
