<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Try to find or create the admin
        $user = User::firstOrCreate(
            ['email' => config('app.admin_email')],
            [
                'name' => config('app.admin_name'),
                'password' => Hash::make(config('app.admin_password')),
                'role' => config('app.admin_role', 'admin'),
                'active' => true,
                'phone' => null,
            ]
        );

        // Optional: update other fields if you want without touching the password
        $user->update([
            'name' => config('app.admin_name'),
            'role' => config('app.admin_role', 'admin'),
            'active' => true,
            'phone' => null,
        ]);
    }
}
