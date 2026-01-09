<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Consent
 */
class Consent extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Consent implements ToSignhostArrayContract, VerificationContract {}
