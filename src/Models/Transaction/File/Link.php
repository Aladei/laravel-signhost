<?php

namespace Noardcode\LaravelSignhost\Models\Transaction\File;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Noardcode\LaravelSignhost\Factories\TransactionFileLinkFactory;
use Noardcode\LaravelSignhost\Models\File;
/**
 * Model Transaction File Link
 *
 * Represents a stored relation to a downloadable resource related to a
 * transaction file (e.g., download URL, receipt URL).
 *
 * @model TransactionFileLink
 */
use Noardcode\LaravelSignhost\Models\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'transaction_file_links';

    protected $guarded = ['updated_at'];

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    protected static function newFactory(): Factory
    {
        return TransactionFileLinkFactory::new();
    }
}
