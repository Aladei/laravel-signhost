<?php

namespace Noardcode\LaravelSignhost\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Model Activity
 *
 * Represents an individual signer activity/state change within a
 * Signhost transaction (e.g., invitation sent, document opened, signed).
 *
 * @model Activity
 */
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Noardcode\LaravelSignhost\Factories\ActivityFactory;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'sh_transaction_activities';

    protected $guarded = ['updated_at'];

    /**
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    protected static function newFactory(): Factory
    {
        return ActivityFactory::new();
    }
}
