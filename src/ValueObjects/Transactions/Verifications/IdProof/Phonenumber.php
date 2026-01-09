<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Phonenumber
 */
class Phonenumber extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Phonenumber implements ToSignhostArrayContract, VerificationContract {}
