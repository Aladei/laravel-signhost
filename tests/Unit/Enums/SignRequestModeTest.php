<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\SignRequestMode;
use Noardcode\LaravelSignhost\Tests\TestCase;

class SignRequestModeTest extends TestCase
{
    public function test_values_and_from(): void
    {
        $this->assertSame(1, SignRequestMode::AtOnce->value);
        $this->assertSame(2, SignRequestMode::Sequential->value);

        $this->assertSame(SignRequestMode::AtOnce, SignRequestMode::from(1));
        $this->assertSame(SignRequestMode::Sequential, SignRequestMode::from(2));
    }
}
