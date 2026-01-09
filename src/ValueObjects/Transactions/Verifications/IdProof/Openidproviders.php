<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Openidproviders
 */
class Openidproviders extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Openidproviders implements ToSignhostArrayContract, VerificationContract {}
