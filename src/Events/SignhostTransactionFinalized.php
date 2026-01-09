<?php

namespace Noardcode\LaravelSignhost\Events;

/**
 * Event SignhostTransactionFinalized
 *
 * Dispatched when a transaction reaches the Signed state (typically after
 * processing the Signhost webhook). Use this to trigger downloading of
 * signed files and receipts or any follow-up workflows.
 *
 * @event SignhostTransactionFinalized
 */
class SignhostTransactionFinalized extends AbstractTransaction {}
