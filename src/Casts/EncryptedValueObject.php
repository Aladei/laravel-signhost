<?php

namespace Noardcode\LaravelSignhost\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EncryptedValueObject
 */
class EncryptedValueObject implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return unserialize(decrypt($value));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        return [$key => encrypt(serialize($value))];
    }
}
