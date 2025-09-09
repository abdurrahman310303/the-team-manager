<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfitShare;
use App\Models\Project;
use App\Models\User;

class ProfitShareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::where('status', 'completed')->get();
        $users = User::with('role')->get();
        
        $statuses = ['pending', 'paid', 'cancelled'];
        
        // Define profit sharing percentages for different roles
        $rolePercentages = [
            'admin' => 30.0,
            'developer' => 25.0,
            'bd' => 20.0,
            'investor' => 25.0
        ];

        // Create profit shares for completed projects
        foreach ($projects as $project) {
            $projectProfit = rand(5000, 50000); // Random profit for the project
            
            foreach ($users as $user) {
                $percentage = $user->role ? ($rolePercentages[$user->role->name] ?? 0) : 0;
                $amount = ($projectProfit * $percentage) / 100;
                $status = $statuses[array_rand($statuses)];
                $calculatedDate = now()->subDays(rand(1, 30));
                $paidDate = $status === 'paid' ? $calculatedDate->addDays(rand(1, 7)) : null;
                
                $notes = [
                    'Profit share calculated based on project performance',
                    'Quarterly profit distribution for completed project',
                    'Performance-based profit allocation',
                    'Project completion bonus distribution',
                    'Revenue sharing based on project success',
                    'Annual profit distribution for team members',
                    'Project milestone achievement bonus',
                    'Success fee distribution for project completion',
                    'Performance incentive payment',
                    'Profit allocation based on role contribution'
                ];

                ProfitShare::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'status' => $status,
                    'calculated_date' => $calculatedDate->format('Y-m-d'),
                    'paid_date' => $paidDate ? $paidDate->format('Y-m-d') : null,
                    'notes' => $notes[array_rand($notes)]
                ]);
            }
        }
    }
}
