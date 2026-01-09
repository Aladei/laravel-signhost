<?php

namespace Noardcode\LaravelSignhost\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Noardcode\LaravelSignhost\Models\Transaction\File\Link;

/**
 * @extends Factory<Link>
 */
class TransactionFileLinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'file_id' => $this->faker->uuid(),
            'rel' => 'file',
            'type' => 'application/pdf',
            'link' => $this->faker->url(),
        ];
    }
}
