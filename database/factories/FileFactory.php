<?php

namespace Noardcode\LaravelSignhost\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Noardcode\LaravelSignhost\Models\File;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'transaction_id' => $this->faker->uuid(),
            'display_name' => 'Document.pdf',
            'original_file_path' => 'transactions/'.$this->faker->uuid().'/original/sample.pdf',
            'signed_file_path' => null,
            'meta_data_exported' => false,
            'content_exported' => false,
            'meta_data' => null,
        ];
    }
}
