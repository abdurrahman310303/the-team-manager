<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Project;
use App\Models\User;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();
        
        $expenseCategories = ['development', 'marketing', 'infrastructure', 'tools', 'travel', 'other'];
        $statuses = ['pending', 'approved', 'rejected'];
        
        $expenseTitles = [
            'development' => [
                'AWS Cloud Services - Monthly',
                'Database Hosting - PostgreSQL',
                'Third-party API Subscriptions',
                'Development Tools License',
                'Code Repository Hosting',
                'SSL Certificate Renewal',
                'Backup Storage Service',
                'Monitoring and Analytics Tools',
                'Development Environment Setup',
                'API Testing Tools'
            ],
            'marketing' => [
                'Google Ads Campaign',
                'Facebook Marketing Budget',
                'LinkedIn Premium Subscription',
                'Content Creation Tools',
                'Email Marketing Platform',
                'SEO Tools and Software',
                'Social Media Management Tools',
                'Marketing Automation Software',
                'Design Software License',
                'Video Production Tools'
            ],
            'infrastructure' => [
                'Server Hosting - Monthly',
                'CDN Service Subscription',
                'Load Balancer Configuration',
                'Security Services',
                'Backup and Recovery Service',
                'Domain Registration',
                'SSL Certificate',
                'Monitoring Services',
                'Cloud Storage Expansion',
                'Network Security Tools'
            ],
            'tools' => [
                'Project Management Software',
                'Design Tools License',
                'Communication Platform',
                'Version Control System',
                'Code Editor License',
                'Testing Tools',
                'Documentation Tools',
                'Collaboration Software',
                'Time Tracking Software',
                'Bug Tracking System'
            ],
            'travel' => [
                'Client Meeting Travel',
                'Conference Attendance',
                'Business Trip Expenses',
                'Client Site Visits',
                'Training Workshop Travel',
                'Networking Event Travel',
                'Client Presentation Travel',
                'Business Development Travel',
                'Team Building Event',
                'Industry Conference'
            ],
            'other' => [
                'Office Supplies',
                'Internet and Phone Bills',
                'Professional Development',
                'Legal and Compliance',
                'Insurance Premiums',
                'Equipment Maintenance',
                'Training and Certification',
                'Consulting Services',
                'Research and Development',
                'Miscellaneous Business Expenses'
            ]
        ];

        // Create 50 sample expenses
        for ($i = 0; $i < 50; $i++) {
            $project = $projects->random();
            $user = $users->random();
            $category = $expenseCategories[array_rand($expenseCategories)];
            $status = $statuses[array_rand($statuses)];
            
            $title = $expenseTitles[$category][array_rand($expenseTitles[$category])];
            $amount = rand(50, 5000);
            $expenseDate = now()->subDays(rand(0, 60));
            
            $descriptions = [
                'Monthly subscription for essential business tools',
                'One-time setup and configuration costs',
                'Ongoing operational expenses for project maintenance',
                'Strategic investment in business growth tools',
                'Essential infrastructure costs for project delivery',
                'Marketing investment to generate new leads',
                'Development tools to improve team productivity',
                'Client acquisition and retention expenses',
                'Operational costs for project execution',
                'Strategic business development investment'
            ];

            Expense::create([
                'project_id' => $project->id,
                'added_by' => $user->id,
                'title' => $title,
                'description' => $descriptions[array_rand($descriptions)],
                'amount' => $amount,
                'category' => $category,
                'expense_date' => $expenseDate->format('Y-m-d'),
                'receipt_url' => 'https://example.com/receipts/receipt_' . rand(1000, 9999) . '.pdf',
                'status' => $status,
                'notes' => 'Expense added for project: ' . $project->name
            ]);
        }
    }
}
