<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Idin;

class IdinTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $idin = new Idin(
            'John Doe',
            'Johnstreet 1',
            '12AB34 Johnville',
            '1970-01-01',
            [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ]
        );

        $this->assertEquals('iDIN', $idin->getType());
        $this->assertEquals('John Doe', $idin->getAccountHolderName());
        $this->assertEquals('Johnstreet 1', $idin->getAccountHolderAddress1());
        $this->assertEquals('12AB34 Johnville', $idin->getAccountHolderAddress2());
        $this->assertEquals('1970-01-01', $idin->getAccountHolderDateOfBirth());
        $this->assertEquals([
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ], $idin->getAttributes());
    }
}
