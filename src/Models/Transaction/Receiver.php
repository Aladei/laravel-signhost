<?php

namespace Noardcode\LaravelSignhost\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Noardcode\LaravelSignhost\Factories\TransactionReceiverFactory;
use Noardcode\LaravelSignhost\Models\Activity;
use Noardcode\LaravelSignhost\Models\Model;
/**
 * Model Transaction Receiver
 *
 * Represents a receiver entity tied to a transaction, typically used for
 * notifications or tracking who should receive artifacts.
 *
 * @model TransactionReceiver
 */
use Noardcode\LaravelSignhost\Models\Transaction;

class Receiver extends Model
{
    use HasFactory;

    protected $table = 'sh_transaction_receivers';

    protected $guarded = ['updated_at'];

    /**
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'transaction_signer_id');
    }

    protected static function newFactory(): Factory
    {
        return TransactionReceiverFactory::new();
    }
}
