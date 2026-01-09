<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Cscqualified;

class CscqualifiedTest extends TestCase
{
    public function test_correct_type_is_returned()
    {
        $cscqualified = new Cscqualified;

        $this->assertEquals('CSC Qualified', $cscqualified->getType());
    }

    public function test_object_can_be_created(): void
    {
        $cscqualified = new Cscqualified;

        $this->assertIsObject($cscqualified);
    }

    public function test_to_array_returns_correct_values(): void
    {
        $cscQualified = new Cscqualified;

        $this->assertEquals([
            'Type' => 'CSC Qualified',
        ], $cscQualified->toArray());
    }
}
