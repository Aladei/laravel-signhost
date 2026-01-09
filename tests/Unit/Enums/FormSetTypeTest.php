<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\FormSetType;
use Noardcode\LaravelSignhost\Tests\TestCase;

class FormSetTypeTest extends TestCase
{
    public function test_values_and_from(): void
    {
        $this->assertSame('Seal', FormSetType::Seal->value);
        $this->assertSame('Signature', FormSetType::Signature->value);
        $this->assertSame('Check', FormSetType::Check->value);
        $this->assertSame('SingleLine', FormSetType::SingleLine->value);

        $this->assertSame(FormSetType::Seal, FormSetType::from('Seal'));
        $this->assertSame(FormSetType::Signature, FormSetType::from('Signature'));
    }
}
