<?php

namespace Noardcode\LaravelSignhost\Contracts;

use Illuminate\Database\Eloquent\Model;
use Noardcode\LaravelSignhost\Models\Transaction;

interface HasSignhostTransactionContract
{
    public function getTransaction(): Transaction|Model;
}
