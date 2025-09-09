<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $developerRole = Role::where('name', 'developer')->first();
        $investorRole = Role::where('name', 'investor')->first();
        $bdRole = Role::where('name', 'bd')->first();

        // Create users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@teammanager.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Developer User',
            'email' => 'developer@teammanager.com',
            'password' => Hash::make('password'),
            'role_id' => $developerRole->id,
        ]);

        User::create([
            'name' => 'Investor User',
            'email' => 'investor@teammanager.com',
            'password' => Hash::make('password'),
            'role_id' => $investorRole->id,
        ]);

        User::create([
            'name' => 'BD User',
            'email' => 'bd@teammanager.com',
            'password' => Hash::make('password'),
            'role_id' => $bdRole->id,
        ]);
    }
}