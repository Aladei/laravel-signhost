<?php

namespace Noardcode\LaravelSignhost\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Noardcode\LaravelSignhost\Models\Transaction\Receiver;

/**
 * @extends Factory<Receiver>
 */
class TransactionReceiverFactory extends Factory
{
    protected $model = Receiver::class;

    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->uuid(),
            'email' => $this->faker->safeEmail(),
            'name' => $this->faker->name(),
            'language' => 'en-US',
            'message' => $this->faker->sentence(),
            'reference' => null,
            'created_date_time' => now()->toIso8601String(),
            'modified_date_time' => now()->toIso8601String(),
        ];
    }
}
