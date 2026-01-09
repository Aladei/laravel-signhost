<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Ideal;

class IdealTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $ideal = new Ideal(
            'NL98INGB0003856625',
            'John Doe',
            'Johnville',
        );

        $this->assertEquals('iDeal', $ideal->getType());
        $this->assertEquals('NL98INGB0003856625', $ideal->getIban());
        $this->assertEquals('John Doe', $ideal->getAccountHolderName());
        $this->assertEquals('Johnville', $ideal->getAccountHolderCity());
    }
}
