<?php

namespace Noardcode\LaravelSignhost\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Noardcode\LaravelSignhost\Contracts\HasSignhostTransactionContract;
use Noardcode\LaravelSignhost\Models\Transaction;

/**
 * Abstract base event for Signhost transaction-related events.
 *
 * Carries the Transaction model instance associated with the event and
 * implements ShouldQueue so listeners can be processed asynchronously.
 *
 * @event AbstractTransaction
 */
abstract class AbstractTransaction implements HasSignhostTransactionContract, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Transaction|Model $transaction;

    /**
     * Create a new event instance.
     *
     * @param  Transaction|Model  $transaction  The related transaction model
     */
    public function __construct(Transaction|Model $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the transaction payload for the event.
     *
     * @return Transaction|Model
     */
    public function getTransaction(): Transaction|Model
    {
        return $this->transaction;
    }
}
