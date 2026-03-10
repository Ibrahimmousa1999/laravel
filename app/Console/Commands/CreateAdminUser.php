<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--email= : Admin email address}
                            {--name= : Admin name}
                            {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get options or ask for input
        $email = $this->option('email') ?: $this->ask('Admin email', 'admin@gmail.com');
        $name = $this->option('name') ?: $this->ask('Admin name', 'Admin');
        $password = $this->option('password') ?: $this->secret('Admin password');

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'name' => 'required|string|min:2',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->warn("User with email {$email} already exists.");
            if ($this->confirm('Do you want to update this user to admin?', true)) {
                $user->update([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'active' => true,
                ]);
                $this->info('✅ Admin user updated successfully!');
            } else {
                $this->info('Operation cancelled.');
                return 0;
            }
        } else {
            // Create new admin
            $user = User::create([
                'email' => $email,
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'active' => true,
                'phone' => null,
            ]);
            $this->info('✅ Admin user created successfully!');
        }

        // Display credentials
        $this->newLine();
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('📧 Email:    ' . $email);
        $this->info('👤 Name:     ' . $name);
        $this->info('🔑 Password: ' . $password);
        $this->info('👑 Role:     admin');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        return 0;
    }
}
