<?php

namespace Noardcode\LaravelSignhost\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

/**
 * Event SignhostIdProofReceived
 *
 * Fired immediately when the IdProof webhook request is received. Carries
 * the incoming Request instance for consumers that need raw payload/headers.
 *
 * @event SignhostIdProofReceived
 */
class SignhostIdProofReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Request $request,
    ) {}
}
