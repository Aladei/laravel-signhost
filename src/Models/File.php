<?php

namespace Noardcode\LaravelSignhost\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Noardcode\LaravelSignhost\Casts\EncryptedValueObject;
/**
 * Model File
 *
 * Represents a document attached to a Transaction, including its
 * storage paths (original/signed), metadata, and exported flags.
 *
 * @model File
 */

use Noardcode\LaravelSignhost\Factories\FileFactory;
use Noardcode\LaravelSignhost\Models\Transaction\File\Link;

class File extends Model
{
    use HasFactory;

    protected $table = 'sh_transaction_files';

    protected $casts = [
        'display_name' => 'encrypted',
        'original_file_path' => 'encrypted',
        'signed_file_path' => 'encrypted',
        'meta_data_exported' => 'boolean',
        'content_exported' => 'boolean',
        'meta_data' => EncryptedValueObject::class,
    ];

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
    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'file_id');
    }

    protected static function newFactory(): Factory
    {
        return FileFactory::new();
    }
}
