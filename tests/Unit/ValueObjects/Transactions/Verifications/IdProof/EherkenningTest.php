<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Eherkenning;

class EherkenningTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $eherkenning = new Eherkenning(
            11122233,
            'abcd-1234'
        );

        $this->assertEquals('eHerkenning', $eherkenning->getType());
        $this->assertEquals('abcd-1234', $eherkenning->getUid());
    }
}
