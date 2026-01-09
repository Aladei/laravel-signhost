<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\SigningCertificate;

class SigningCertificateTest extends TestCase
{
    public function test_correct_type_is_returned()
    {
        $signingcertificate = new SigningCertificate(
            'Issuer',
            'Subject',
            'Thumbprint'
        );

        $this->assertEquals('SigningCertificate', $signingcertificate->getType());
        $this->assertEquals('Issuer', $signingcertificate->getIssuer());
        $this->assertEquals('Subject', $signingcertificate->getSubject());
        $this->assertEquals('Thumbprint', $signingcertificate->getThumbprint());
    }
}
