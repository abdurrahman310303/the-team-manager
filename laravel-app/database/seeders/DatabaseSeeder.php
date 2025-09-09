<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@teammanager.com',
            'role_id' => 1, // Admin role
        ]);

        // Create other role users
        User::factory()->create([
            'name' => 'Developer User',
            'email' => 'developer@teammanager.com',
            'role_id' => 2, // Developer role
        ]);

        User::factory()->create([
            'name' => 'Investor User',
            'email' => 'investor@teammanager.com',
            'role_id' => 3, // Investor role
        ]);

        User::factory()->create([
            'name' => 'BD User',
            'email' => 'bd@teammanager.com',
            'role_id' => 4, // BD role
        ]);

        // Seed sample data
        $this->call([
            ProjectSeeder::class,
            LeadSeeder::class,
            DailyReportSeeder::class,
            ExpenseSeeder::class,
            PaymentSeeder::class,
            ProfitShareSeeder::class,
        ]);
    }
}
