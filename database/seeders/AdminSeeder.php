<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Get admin credentials from config with fallbacks
        $adminEmail = config('app.admin_email') ?: env('ADMIN_EMAIL', 'admin@gmail.com');
        $adminName = config('app.admin_name') ?: env('ADMIN_NAME', 'Admin');
        $adminPassword = config('app.admin_password') ?: env('ADMIN_PASSWORD', '1212qwqwQ#');
        $adminRole = config('app.admin_role') ?: env('ADMIN_ROLE', 'admin');

        // Debug output
        $this->command->info("Creating admin with email: {$adminEmail}");

        // Check if user already exists
        $existingUser = User::where('email', $adminEmail)->first();

        if ($existingUser) {
            $this->command->warn("Admin user already exists. Updating password...");
            // Update existing user's password
            $existingUser->update([
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'role' => $adminRole,
                'active' => true,
            ]);
            $this->command->info("Admin user updated successfully!");
        } else {
            // Create new admin user
            $user = User::create([
                'email' => $adminEmail,
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'role' => $adminRole,
                'active' => true,
                'phone' => null,
            ]);
            $this->command->info("Admin user created successfully!");
        }

        $this->command->info("Admin credentials:");
        $this->command->info("Email: {$adminEmail}");
        $this->command->info("Password: {$adminPassword}");
    }
}
