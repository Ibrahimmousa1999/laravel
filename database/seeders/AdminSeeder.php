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
            ['email' => config('app.admin_email')],
            [
                'name' => config('app.admin_name'),
                'password' => Hash::make(config('app.admin_password')),
                'role' => config('app.admin_role', 'admin'),
                'active' => true,
                'phone' => null,
            ]
        );
        // Set password only if user is newly created
        $user = User::firstWhere('email', config('app.admin_email'));
        if (!$user->wasRecentlyCreated) {
            $user->password = Hash::make(config('app.admin_password'));
            $user->save();
        }
    }
}
