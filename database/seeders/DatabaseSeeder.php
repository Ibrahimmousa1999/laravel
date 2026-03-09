<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Always create/update the fixed admin user
        $this->call([
            AdminSeeder::class,
        ]);
    }
}
