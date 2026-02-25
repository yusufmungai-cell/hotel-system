<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        // ROLE
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin'
        ]);

        // ADMIN USER
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id
            ]
        );

        // Ensure role attached
        $admin->role_id = $adminRole->id;
        $admin->save();
    }
}
