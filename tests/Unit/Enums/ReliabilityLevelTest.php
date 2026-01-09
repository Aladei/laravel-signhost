<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\ReliabilityLevel;
use Noardcode\LaravelSignhost\Tests\TestCase;

class ReliabilityLevelTest extends TestCase
{
    public function test_values_and_labels(): void
    {
        $this->assertSame('Basis', ReliabilityLevel::Basic->value);
        $this->assertSame('Midden', ReliabilityLevel::Medium->value);
        $this->assertSame('Substantieel', ReliabilityLevel::Substantial->value);
        $this->assertSame('Hoog', ReliabilityLevel::High->value);

        $this->assertSame('Basic', ReliabilityLevel::Basic->label());
        $this->assertSame('Medium', ReliabilityLevel::Medium->label());
        $this->assertSame('Substantial', ReliabilityLevel::Substantial->label());
        $this->assertSame('High', ReliabilityLevel::High->label());
    }
}
