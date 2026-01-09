<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Itsmesign;

class ItsmesignTest extends TestCase
{
    public function test_correct_type_is_returned()
    {
        $itsmesign = new Itsmesign(
            'Issuer',
            'Subject',
            'Thumbprint'
        );

        $this->assertEquals('itsme sign', $itsmesign->getType());
        $this->assertEquals('Issuer', $itsmesign->getIssuer());
        $this->assertEquals('Subject', $itsmesign->getSubject());
        $this->assertEquals('Thumbprint', $itsmesign->getThumbprint());
    }
}
