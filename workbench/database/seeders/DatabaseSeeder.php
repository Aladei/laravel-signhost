<?php

namespace Noardcode\LaravelSignhost\Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Noardcode\LaravelSignhost\Factories\TransactionFactory;
use Noardcode\LaravelSignhost\Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserFactory::new()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        //        TransactionFactory::new()->create();
    }
}
