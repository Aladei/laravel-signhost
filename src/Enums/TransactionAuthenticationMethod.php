<?php

namespace Noardcode\LaravelSignhost\Enums;

use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Digid;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Phonenumber;

/**
 * Enum TransactionAuthenticationMethod
 *
 * Lists the authentication mechanisms a signer can use to access
 * the signing flow (separate from identity verification methods).
 *
 * @enum TransactionAuthenticationMethod
 */
enum TransactionAuthenticationMethod: string
{
    case Digid = Digid::class;
    case Phonenumber = Phonenumber::class;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Digid => 'DigiD',
            self::Phonenumber => 'PhoneNumber',
        };
    }
}
