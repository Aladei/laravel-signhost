<?php

namespace Noardcode\LaravelSignhost\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade Signhost
 *
 * Entry point for consuming Signhost services within applications.
 * Provides access to high-level services like Signing and IdProof via
 * the underlying SignhostService container binding.
 *
 * @facade Signhost
 */
class Signhost extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SignhostService::class;
    }
}
