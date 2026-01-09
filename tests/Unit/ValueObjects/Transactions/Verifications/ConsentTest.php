<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Consent;

class ConsentTest extends TestCase
{
    public function test_correct_type_is_returned()
    {
        $consent = new Consent;

        $this->assertEquals('Consent', $consent->getType());
    }

    public function test_object_can_be_created(): void
    {
        $consent = new Consent;

        $this->assertIsObject($consent);
    }

    public function test_to_array_returns_correct_values(): void
    {
        $consent = new Consent;

        $this->assertEquals([
            'Type' => 'Consent',
        ], $consent->toArray());
    }
}
