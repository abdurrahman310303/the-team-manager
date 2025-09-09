<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $investor = User::whereHas('role', function($q) {
            $q->where('name', 'investor');
        })->first();
        
        $paymentTypes = ['investment', 'expense_reimbursement', 'profit_share'];
        $statuses = ['pending', 'completed', 'failed'];
        
        $paymentDescriptions = [
            'investment' => [
                'Initial project investment for development phase',
                'Additional funding for project expansion',
                'Strategic investment for market penetration',
                'Capital injection for project acceleration',
                'Investment for technology upgrade',
                'Funding for team expansion',
                'Investment for marketing campaign',
                'Capital for infrastructure development',
                'Strategic investment for growth',
                'Funding for project completion'
            ],
            'expense_reimbursement' => [
                'Reimbursement for approved project expenses',
                'Refund for overpaid project costs',
                'Reimbursement for travel expenses',
                'Refund for cancelled services',
                'Reimbursement for equipment purchases',
                'Refund for unused subscriptions',
                'Reimbursement for training costs',
                'Refund for marketing expenses',
                'Reimbursement for development tools',
                'Refund for infrastructure costs'
            ],
            'profit_share' => [
                'Quarterly profit distribution',
                'Project completion bonus',
                'Performance-based profit share',
                'Annual profit distribution',
                'Project milestone bonus',
                'Revenue sharing payment',
                'Success fee distribution',
                'Project profit allocation',
                'Performance incentive payment',
                'Profit sharing distribution'
            ]
        ];

        // Create 30 sample payments
        for ($i = 0; $i < 30; $i++) {
            $project = $projects->random();
            $paymentType = $paymentTypes[array_rand($paymentTypes)];
            $status = $statuses[array_rand($statuses)];
            
            $description = $paymentDescriptions[$paymentType][array_rand($paymentDescriptions[$paymentType])];
            
            // Different amount ranges based on payment type
            if ($paymentType === 'investment') {
                $amount = rand(5000, 50000);
            } elseif ($paymentType === 'expense_reimbursement') {
                $amount = rand(100, 5000);
            } else { // profit_share
                $amount = rand(1000, 25000);
            }
            
            $paymentDate = now()->subDays(rand(0, 90));
            
            Payment::create([
                'project_id' => $project->id,
                'investor_id' => $investor->id,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'payment_date' => $paymentDate->format('Y-m-d'),
                'description' => $description,
                'reference_number' => 'PAY-' . strtoupper(substr($paymentType, 0, 3)) . '-' . rand(100000, 999999),
                'status' => $status,
                'notes' => 'Payment for project: ' . $project->name . ' - ' . ucfirst(str_replace('_', ' ', $paymentType))
            ]);
        }
    }
}
