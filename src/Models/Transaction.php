<?php

namespace Noardcode\LaravelSignhost\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Factories\TransactionFactory;
use Noardcode\LaravelSignhost\Models\Transaction\Receiver;

/**
 * Model Transaction
 *
 * Represents a Signhost transaction within the application. Holds
 * relationships to receivers, files, and activities, as well as
 * status, context, and helper accessors.
 *
 * @model Transaction
 */
class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'sh_transactions';

    protected $casts = [
        'object' => 'encrypted:object',
        'finalized' => 'boolean',
        'context' => 'encrypted:object',
        'webhook_response' => 'encrypted:object',
        'created_date_time' => 'datetime',
        'modified_date_time' => 'datetime',
        'cancelled_date_time' => 'datetime',
    ];

    /**
     * The transaction status is stored in the `status` column (integer). Expose
     * it as a TransactionStatus enum under `status_code` (the name used across
     * the package: webhook handler, poll, faker and consumers). Reading/writing
     * `status_code` transparently maps to the `status` column.
     */
    public function statusCode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status !== null ? TransactionStatus::tryFrom((int) $this->status) : null,
            set: fn ($value) => ['status' => $value instanceof TransactionStatus ? $value->value : $value],
        );
    }

    /**
     * @return HasMany
     */
    public function receivers(): HasMany
    {
        return $this->hasMany(Receiver::class);
    }

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * @return HasMany
     */
    public function signedDocuments(): HasMany
    {
        return $this->hasMany(File::class)->whereNotNull('signed_file_path');
    }

    /**
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * @return object|null
     */
    public function getLastActivityAttribute(): ?object
    {
        return $this->hasOne(Activity::class)->latest()->first();
    }

    /**
     * @return Attribute
     */
    public function options(): Attribute
    {
        return Attribute::make(
            fn ($value) => unserialize($value),
            fn ($value) => serialize($value)
        );
    }

    protected static function newFactory(): Factory
    {
        return TransactionFactory::new();
    }
}
