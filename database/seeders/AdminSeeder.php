<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // ensure no duplicate
            [
                'name' => 'Admin',
                'password' => Hash::make('1212qwqwQ#'), // set secure password
            ]
        );
    }
}
