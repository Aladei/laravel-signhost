<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\TransactionType;
use Noardcode\LaravelSignhost\Tests\TestCase;

class TransactionTypeTest extends TestCase
{
    public function test_values_and_labels(): void
    {
        $this->assertSame('document_sign', TransactionType::DocumentSign->value);
        $this->assertSame('id_proof', TransactionType::IdProof->value);
        $this->assertSame('unknown', TransactionType::Unknown->value);

        $this->assertSame('Document Sign', TransactionType::DocumentSign->label());
        $this->assertSame('ID Proof', TransactionType::IdProof->label());
        $this->assertSame('Unknown', TransactionType::Unknown->label());
    }
}
