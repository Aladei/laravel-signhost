<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Itsmeidentification;

class ItsmeidentificationTest extends TestCase
{
    /**
     * @throws SignhostException
     */
    public function test_correct_values_are_returned()
    {
        $itsmeidentification = new Itsmeidentification(
            '+32612345678',
            [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ]
        );

        $this->assertEquals('itsme Identification', $itsmeidentification->getType());
        $this->assertEquals('+32612345678', $itsmeidentification->getPhoneNumber());
        $this->assertEquals([
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ], $itsmeidentification->getAttributes());
    }
}
