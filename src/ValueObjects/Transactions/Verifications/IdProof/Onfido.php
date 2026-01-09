<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Onfido
 */
class Onfido extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Onfido implements ToSignhostArrayContract, VerificationContract {}
