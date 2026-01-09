<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Surfnet;

class SurfnetTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $surfnet = new Surfnet(
            'abcd-1234',
            [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ]
        );

        $this->assertEquals('SURFnet', $surfnet->getType());
        $this->assertEquals('abcd-1234', $surfnet->getUid());
        $this->assertEquals([
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ], $surfnet->getAttributes());
    }
}
