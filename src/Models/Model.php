<?php

namespace Noardcode\LaravelSignhost\Models;

use Aladei\Tenancy\Traits\Models\UseTenantConnection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;

/**
 * Abstract base Eloquent model for the package.
 *
 * - Uses UUIDs (string keyType, non-incrementing) by default.
 * - Applies a consistent table prefix (sh_) unless an explicit
 *   prefixed table name is already set on the model.
 * - Provides guarded timestamps by default.
 *
 * @model BaseModel
 */
abstract class Model extends EloquentModel
{
    use UseTenantConnection;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if (Str::startsWith($this->table, 'sh_')) {
            return $this->table;
        }

        return 'sh_'.($this->table ?? Str::snake(Str::pluralStudly(class_basename($this))));
    }
}
