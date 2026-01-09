<?php

namespace Noardcode\LaravelSignhost\Enums;

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Consent;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Cscqualified;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Digid;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eherkenning;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Eidaslogin;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ideal;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Idin;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Ipaddress;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmeidentification;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Itsmesign;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Onfido;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Openidproviders;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Phonenumber;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Scribble;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\SigningCertificate;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Surfnet;

/**
 * Enum TransactionVerificationMethod
 *
 * Specifies the verification methods that can be required from a signer
 * (e.g., iDIN, DigiD, iDEAL, itsme), proving identity or intent.
 *
 * @enum TransactionVerificationMethod
 */
enum TransactionVerificationMethod: string
{
    case Consent = Consent::class;
    case Cscqualified = Cscqualified::class;
    case Digid = Digid::class;
    case Eherkenning = Eherkenning::class;
    case Eidaslogin = Eidaslogin::class;
    case Ideal = Ideal::class;
    case Idin = Idin::class;
    case Ipaddress = Ipaddress::class;
    case Itsmeidentification = Itsmeidentification::class;
    case ItsmeSign = Itsmesign::class;
    case Onfido = Onfido::class;
    case Openidproviders = Openidproviders::class;
    case Phonenumber = Phonenumber::class;
    case Scribble = Scribble::class;
    case SigningCertificate = SigningCertificate::class;
    case Surfnet = Surfnet::class;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Consent => 'Consent',
            self::Cscqualified => 'CSC Qualified',
            self::Digid => 'DigiD',
            self::Eherkenning => 'eHerkenning',
            self::Eidaslogin => 'eIDAS Login',
            self::Ideal => 'iDeal',
            self::Idin => 'iDIN',
            self::Ipaddress => '',
            self::Itsmeidentification => 'itsme Identification',
            self::ItsmeSign => 'itsme Sign',
            self::Onfido => 'Onfido',
            self::Openidproviders => 'OpenID Providers',
            self::Phonenumber => 'PhoneNumber',
            self::Scribble => 'Scribble',
            self::SigningCertificate => 'SigningCertificate',
            self::Surfnet => 'SURFnet',
        };
    }
}
