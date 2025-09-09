<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DailyReport;
use App\Models\User;
use App\Models\Project;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and projects
        $developer = User::whereHas('role', function($q) {
            $q->where('name', 'developer');
        })->first();
        
        $bd = User::whereHas('role', function($q) {
            $q->where('name', 'bd');
        })->first();
        
        $projects = Project::all();

        // Create developer daily reports for the last 14 days
        for ($i = 0; $i < 14; $i++) {
            $date = now()->subDays($i);
            $project = $projects->where('project_manager_id', $developer->id)->random();
            
            $workCompleted = [
                'Implemented user authentication system with JWT tokens',
                'Completed payment gateway integration for Stripe',
                'Fixed critical bug in shopping cart functionality',
                'Added responsive design for mobile devices',
                'Implemented real-time notifications system',
                'Completed database optimization and indexing',
                'Added comprehensive error handling and logging',
                'Implemented file upload functionality with validation',
                'Completed API documentation and testing',
                'Added automated testing suite with 95% coverage',
                'Implemented caching system for better performance',
                'Completed security audit and vulnerability fixes',
                'Added analytics dashboard with real-time data',
                'Implemented backup and recovery system'
            ];
            
            $challenges = [
                'Faced integration issues with third-party API',
                'Performance optimization challenges with large datasets',
                'Cross-browser compatibility issues',
                'Database connection timeout problems',
                'Memory leaks in JavaScript components',
                'SSL certificate configuration issues',
                'Mobile responsive design challenges',
                'API rate limiting constraints',
                'Database migration complications',
                'Third-party service downtime',
                'Code refactoring complexity',
                'Testing environment setup issues',
                'Deployment pipeline failures',
                'User experience optimization challenges'
            ];
            
            $nextPlans = [
                'Continue with frontend development and testing',
                'Implement advanced search functionality',
                'Add more payment gateway options',
                'Optimize database queries and performance',
                'Complete mobile app development',
                'Add comprehensive logging and monitoring',
                'Implement advanced security features',
                'Complete user documentation',
                'Add automated deployment pipeline',
                'Implement advanced analytics features',
                'Complete performance optimization',
                'Add comprehensive error reporting',
                'Implement advanced caching strategies',
                'Complete final testing and deployment'
            ];

            DailyReport::create([
                'user_id' => $developer->id,
                'project_id' => $project->id,
                'report_type' => 'developer',
                'report_date' => $date->format('Y-m-d'),
                'work_completed' => $workCompleted[array_rand($workCompleted)],
                'challenges_faced' => $challenges[array_rand($challenges)],
                'next_plans' => $nextPlans[array_rand($nextPlans)],
                'hours_worked' => rand(6, 10),
                'leads_generated' => 0,
                'proposals_submitted' => 0,
                'projects_locked' => 0,
                'revenue_generated' => 0,
                'notes' => 'Productive day with good progress on core features.'
            ]);
        }

        // Create BD daily reports for the last 14 days
        for ($i = 0; $i < 14; $i++) {
            $date = now()->subDays($i);
            $project = $projects->where('project_manager_id', $bd->id)->random();
            
            $workCompleted = [
                'Conducted market research for new client opportunities',
                'Prepared and sent 5 proposals to potential clients',
                'Followed up with existing leads and prospects',
                'Attended networking event and generated 3 new leads',
                'Created marketing materials for upcoming campaign',
                'Conducted client discovery calls and needs assessment',
                'Prepared detailed project proposals and cost estimates',
                'Coordinated with development team on client requirements',
                'Updated CRM with latest lead information and status',
                'Conducted competitor analysis and market positioning',
                'Prepared presentation for client pitch meeting',
                'Followed up on pending proposals and negotiations',
                'Generated social media content for brand awareness',
                'Conducted client satisfaction survey and feedback'
            ];
            
            $challenges = [
                'Client budget constraints affecting proposal acceptance',
                'Long sales cycle with enterprise clients',
                'Competition from established players in market',
                'Client indecision on project scope and timeline',
                'Economic uncertainty affecting client spending',
                'Technical complexity requiring development team input',
                'Client expectations not aligned with capabilities',
                'Market saturation in target industry',
                'Pricing pressure from competitors',
                'Client internal approval process delays',
                'Seasonal fluctuations in business activity',
                'Limited marketing budget affecting lead generation',
                'Client changing requirements mid-proposal',
                'Economic downturn affecting client budgets'
            ];
            
            $nextPlans = [
                'Follow up on submitted proposals and negotiations',
                'Prepare for upcoming client presentation',
                'Research new market opportunities and verticals',
                'Develop strategic partnerships and alliances',
                'Create targeted marketing campaigns for Q1',
                'Conduct client needs assessment and discovery',
                'Prepare detailed project proposals and estimates',
                'Attend industry conferences and networking events',
                'Follow up with warm leads and prospects',
                'Develop case studies and success stories',
                'Create content marketing strategy and materials',
                'Conduct competitive analysis and positioning',
                'Prepare for quarterly business review',
                'Develop new service offerings and packages'
            ];

            DailyReport::create([
                'user_id' => $bd->id,
                'project_id' => $project->id,
                'report_type' => 'bd',
                'report_date' => $date->format('Y-m-d'),
                'work_completed' => $workCompleted[array_rand($workCompleted)],
                'challenges_faced' => $challenges[array_rand($challenges)],
                'next_plans' => $nextPlans[array_rand($nextPlans)],
                'hours_worked' => rand(7, 9),
                'leads_generated' => rand(1, 5),
                'proposals_submitted' => rand(2, 6),
                'projects_locked' => rand(0, 2),
                'revenue_generated' => rand(5000, 25000),
                'notes' => 'Good progress on lead generation and client outreach.'
            ]);
        }
    }
}
