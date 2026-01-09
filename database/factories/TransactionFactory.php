<?php

namespace Noardcode\LaravelSignhost\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Noardcode\LaravelSignhost\Enums\SignRequestMode;
use Noardcode\LaravelSignhost\Enums\TransactionType;
use Noardcode\LaravelSignhost\Models\Transaction;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'type' => TransactionType::DocumentSign->value,
            'status' => 20,
            'seal' => false,
            'reference' => null,
            'postback_url' => null,
            'sign_request_mode' => SignRequestMode::AtOnce->value,
            'days_to_expire' => 60,
            'send_email_notifications' => false,
            'created_date_time' => now()->toIso8601String(),
            'modified_date_time' => now()->toIso8601String(),
            'canceled_date_time' => null,
            'authenticated' => false,
            'probability' => 0,
            'context' => null,
            'receipt' => null,
            'object' => null,
        ];
    }
}
