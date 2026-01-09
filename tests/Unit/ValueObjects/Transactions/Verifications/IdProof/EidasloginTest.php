<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Eidaslogin;

class EidasloginTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $eidaslogin = new Eidaslogin(
            'abcd-1234',
            '1',
            'John',
            'Doe',
            '1980-01-01',
            [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ]
        );

        $this->assertEquals('eIDAS Login', $eidaslogin->getType());
        $this->assertEquals('abcd-1234', $eidaslogin->getUid());
        $this->assertEquals('1', $eidaslogin->getLevel());
        $this->assertEquals('John', $eidaslogin->getFirstName());
        $this->assertEquals('Doe', $eidaslogin->getLastName());
        $this->assertEquals('1980-01-01', $eidaslogin->getDateOfBirth());
        $this->assertEquals([
            'attribute1' => 'value1',
            'attribute2' => 'value2',
        ], $eidaslogin->getAttributes());
    }
}
