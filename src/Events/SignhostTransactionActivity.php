<?php

namespace Noardcode\LaravelSignhost\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

/**
 * Event SignhostTransactionReceived
 *
 * Fired when a Signhost transaction webhook HTTP request is received.
 * Carries the full incoming Request for consumers that need access to
 * raw payload and headers.
 *
 * @event SignhostTransactionReceived
 */
class SignhostTransactionActivity
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Request $request) {}
}
