<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users with BD and admin roles
        $bdUsers = User::whereHas('role', function($query) {
            $query->whereIn('name', ['bd', 'admin']);
        })->get();

        // Get the BD user specifically (role_id = 4)
        $bdUser = User::where('role_id', 4)->first();

        // Get some projects for lead association
        $projects = Project::take(3)->get();

        $leads = [
            [
                'company_name' => 'TechCorp Solutions',
                'contact_person' => 'John Smith',
                'email' => 'john.smith@techcorp.com',
                'phone' => '+1-555-0123',
                'description' => 'Looking for a custom web application for their business operations.',
                'status' => 'new',
                'source' => 'website',
                'estimated_value' => 15000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => $projects->first()?->id,
                'last_contact_date' => now()->subDays(2),
                'notes' => 'Initial inquiry received through contact form.',
            ],
            [
                'company_name' => 'Digital Marketing Pro',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@digitalmarketingpro.com',
                'phone' => '+1-555-0456',
                'description' => 'Need a comprehensive digital marketing platform with analytics.',
                'status' => 'contacted',
                'source' => 'referral',
                'estimated_value' => 25000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => $projects->skip(1)->first()?->id,
                'last_contact_date' => now()->subDays(1),
                'notes' => 'Referred by existing client. Very interested in our services.',
            ],
            [
                'company_name' => 'E-commerce Plus',
                'contact_person' => 'Mike Wilson',
                'email' => 'mike@ecommerceplus.com',
                'phone' => '+1-555-0789',
                'description' => 'Requires a complete e-commerce solution with payment integration.',
                'status' => 'qualified',
                'source' => 'cold_call',
                'estimated_value' => 35000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => $projects->skip(2)->first()?->id,
                'last_contact_date' => now(),
                'notes' => 'Qualified lead after initial call. Budget confirmed.',
            ],
            [
                'company_name' => 'Startup Ventures',
                'contact_person' => 'Emily Davis',
                'email' => 'emily@startupventures.com',
                'phone' => '+1-555-0321',
                'description' => 'Looking for MVP development for their fintech startup.',
                'status' => 'proposal_sent',
                'source' => 'social_media',
                'estimated_value' => 45000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => null,
                'last_contact_date' => now()->subDays(3),
                'notes' => 'Proposal sent. Waiting for response.',
            ],
            [
                'company_name' => 'Global Enterprises',
                'contact_person' => 'Robert Brown',
                'email' => 'robert@globalenterprises.com',
                'phone' => '+1-555-0654',
                'description' => 'Enterprise-level CRM system with custom integrations.',
                'status' => 'negotiating',
                'source' => 'advertisement',
                'estimated_value' => 75000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => null,
                'last_contact_date' => now()->subDays(1),
                'notes' => 'In negotiation phase. Discussing contract terms.',
            ],
            [
                'company_name' => 'Local Business Co',
                'contact_person' => 'Lisa Anderson',
                'email' => 'lisa@localbusinessco.com',
                'phone' => '+1-555-0987',
                'description' => 'Small business website with online booking system.',
                'status' => 'closed_won',
                'source' => 'website',
                'estimated_value' => 8000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => null,
                'last_contact_date' => now()->subDays(5),
                'notes' => 'Deal closed successfully. Project started.',
            ],
            [
                'company_name' => 'Failed Lead Inc',
                'contact_person' => 'Tom Wilson',
                'email' => 'tom@failedlead.com',
                'phone' => '+1-555-0111',
                'description' => 'Lead that did not convert due to budget constraints.',
                'status' => 'closed_lost',
                'source' => 'cold_call',
                'estimated_value' => 12000.00,
                'assigned_to' => $bdUser->id,
                'project_id' => null,
                'last_contact_date' => now()->subDays(10),
                'notes' => 'Budget too low for our services. Lead closed as lost.',
            ],
        ];

        foreach ($leads as $leadData) {
            Lead::create($leadData);
        }
    }
}