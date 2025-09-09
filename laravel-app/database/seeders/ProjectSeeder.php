<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users by role
        $admin = User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->first();
        
        $developer = User::whereHas('role', function($q) {
            $q->where('name', 'developer');
        })->first();
        
        $bd = User::whereHas('role', function($q) {
            $q->where('name', 'bd');
        })->first();
        
        $investor = User::whereHas('role', function($q) {
            $q->where('name', 'investor');
        })->first();

        // Create sample projects
        $projects = [
            [
                'name' => 'E-Commerce Platform Development',
                'description' => 'Building a comprehensive e-commerce platform with modern features including payment integration, inventory management, and user dashboard.',
                'status' => 'in_progress',
                'budget' => 50000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
                'project_manager_id' => $developer->id,
                'client_id' => null,
            ],
            [
                'name' => 'Mobile App for Food Delivery',
                'description' => 'Developing a cross-platform mobile application for food delivery service with real-time tracking and payment features.',
                'status' => 'in_progress',
                'budget' => 35000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'project_manager_id' => $developer->id,
                'client_id' => null,
            ],
            [
                'name' => 'Corporate Website Redesign',
                'description' => 'Complete redesign of corporate website with modern UI/UX, responsive design, and CMS integration.',
                'status' => 'completed',
                'budget' => 15000.00,
                'start_date' => now()->subDays(90),
                'end_date' => now()->subDays(30),
                'project_manager_id' => $developer->id,
                'client_id' => null,
            ],
            [
                'name' => 'SaaS Dashboard Development',
                'description' => 'Building a comprehensive SaaS dashboard with analytics, user management, and subscription features.',
                'status' => 'planning',
                'budget' => 75000.00,
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(90),
                'project_manager_id' => $developer->id,
                'client_id' => null,
            ],
            [
                'name' => 'Marketing Campaign - Q4 2024',
                'description' => 'Comprehensive digital marketing campaign including social media, content marketing, and PPC advertising.',
                'status' => 'in_progress',
                'budget' => 25000.00,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(20),
                'project_manager_id' => $bd->id,
                'client_id' => null,
            ],
            [
                'name' => 'Client Acquisition Project',
                'description' => 'Strategic project focused on acquiring new enterprise clients through targeted outreach and proposals.',
                'status' => 'in_progress',
                'budget' => 10000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'project_manager_id' => $bd->id,
                'client_id' => null,
            ],
            [
                'name' => 'Legacy System Migration',
                'description' => 'Migrating legacy systems to modern cloud infrastructure with zero downtime.',
                'status' => 'on_hold',
                'budget' => 40000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(30),
                'project_manager_id' => $developer->id,
                'client_id' => null,
            ],
            [
                'name' => 'Partnership Development Initiative',
                'description' => 'Developing strategic partnerships with key industry players to expand market reach.',
                'status' => 'completed',
                'budget' => 5000.00,
                'start_date' => now()->subDays(120),
                'end_date' => now()->subDays(60),
                'project_manager_id' => $bd->id,
                'client_id' => null,
            ]
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }
    }
}
