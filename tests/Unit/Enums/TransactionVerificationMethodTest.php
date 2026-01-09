<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\TransactionVerificationMethod;
use Noardcode\LaravelSignhost\Tests\TestCase;

class TransactionVerificationMethodTest extends TestCase
{
    public function test_cases_and_labels(): void
    {
        $this->assertSame('Consent', TransactionVerificationMethod::Consent->label());
        $this->assertSame('CSC Qualified', TransactionVerificationMethod::Cscqualified->label());
        $this->assertSame('DigiD', TransactionVerificationMethod::Digid->label());
        $this->assertSame('eHerkenning', TransactionVerificationMethod::Eherkenning->label());
        $this->assertSame('eIDAS Login', TransactionVerificationMethod::Eidaslogin->label());
        $this->assertSame('iDeal', TransactionVerificationMethod::Ideal->label());
        $this->assertSame('iDIN', TransactionVerificationMethod::Idin->label());
        $this->assertSame('', TransactionVerificationMethod::Ipaddress->label());
        $this->assertSame('itsme Identification', TransactionVerificationMethod::Itsmeidentification->label());
        $this->assertSame('itsme Sign', TransactionVerificationMethod::ItsmeSign->label());
        $this->assertSame('Onfido', TransactionVerificationMethod::Onfido->label());
        $this->assertSame('OpenID Providers', TransactionVerificationMethod::Openidproviders->label());
        $this->assertSame('PhoneNumber', TransactionVerificationMethod::Phonenumber->label());
        $this->assertSame('Scribble', TransactionVerificationMethod::Scribble->label());
        $this->assertSame('SigningCertificate', TransactionVerificationMethod::SigningCertificate->label());
        $this->assertSame('SURFnet', TransactionVerificationMethod::Surfnet->label());
    }
}
