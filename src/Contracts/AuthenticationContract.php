<?php

namespace Noardcode\LaravelSignhost\Contracts;

interface AuthenticationContract
{
    public function getType(): string;
}
