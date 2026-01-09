<?php

namespace Noardcode\LaravelSignhost\Events;

/**
 * Event SignhostTransactionCreated
 *
 * Dispatched after a transaction has been created (locally and via the
 * Signhost API). The Transaction model is available through
 * AbstractTransaction::getTransaction().
 *
 * @event SignhostTransactionCreated
 */
class SignhostTransactionCreated extends AbstractTransaction {}
