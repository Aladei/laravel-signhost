<?php

namespace Noardcode\LaravelSignhost\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Noardcode\LaravelSignhost\Enums\SignerActivityStatus;
use Noardcode\LaravelSignhost\Models\Activity;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->uuid(),
            'transaction_signer_id' => null,
            'state' => SignerActivityStatus::InvitationSent,
            'state_code' => SignerActivityStatus::InvitationSent->value,
            'created_at' => now(),
        ];
    }
}
