<?php

namespace Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Contracts\ToSignhostArrayContract;
use Noardcode\LaravelSignhost\Contracts\VerificationContract;

/**
 * Class Scribble
 */
class Scribble extends \Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\Scribble implements ToSignhostArrayContract, VerificationContract {}
