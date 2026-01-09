<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Enums\ReliabilityLevel;
use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Digid;

class DigidTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $digid = new Digid(
            111222333,
            ReliabilityLevel::High
        );

        $this->assertEquals('111222333', $digid->getBsn());
        $this->assertEquals(ReliabilityLevel::High, $digid->getReliabilityLevel());
    }
}
