<?php

namespace Noardcode\LaravelSignhost\Events;

/**
 * Event SignhostTransactionStarted
 *
 * Dispatched after the transaction has been started on Signhost, enabling
 * the signing flow for all configured signers.
 *
 * @event SignhostTransactionStarted
 */
class SignhostTransactionStarted extends AbstractTransaction {}
