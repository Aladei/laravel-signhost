<?php

namespace Noardcode\LaravelSignhost\Events;

/**
 * Event SignhostTransactionDeleted
 *
 * Dispatched after a transaction has been deleted via the Signhost API
 * and removed locally.
 *
 * @event SignhostTransactionDeleted
 */
class SignhostTransactionDeleted extends AbstractTransaction {}
