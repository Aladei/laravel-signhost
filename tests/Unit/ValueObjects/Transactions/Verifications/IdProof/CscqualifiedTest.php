<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Cscqualified;

class CscqualifiedTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $cscqualified = new Cscqualified(
            'Issuer',
            'Subject',
            'Thumbprint',
            'Provider'
        );

        $this->assertEquals('Issuer', $cscqualified->getIssuer());
        $this->assertEquals('Subject', $cscqualified->getSubject());
        $this->assertEquals('Thumbprint', $cscqualified->getThumbprint());
        $this->assertEquals('Provider', $cscqualified->getProvider());
    }
}
