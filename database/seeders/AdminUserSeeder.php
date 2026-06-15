<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dailydrive.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@dailydrive.com',
                'password' => Hash::make('admin123'),
                'role'     => 'super_admin',
            ]
        );

        $this->command->info('Admin user created: admin@dailydrive.com / admin123');
    }
}
