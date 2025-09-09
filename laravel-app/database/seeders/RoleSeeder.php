<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access and management capabilities',
            ],
            [
                'name' => 'developer',
                'display_name' => 'Developer',
                'description' => 'Technical development and project implementation',
            ],
            [
                'name' => 'investor',
                'display_name' => 'Investor',
                'description' => 'Investment oversight and financial decision making',
            ],
            [
                'name' => 'bd',
                'display_name' => 'Business Development',
                'description' => 'Lead generation, client relations, and business growth',
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
