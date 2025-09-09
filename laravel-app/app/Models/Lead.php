<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'description',
        'status',
        'source',
        'estimated_value',
        'assigned_to',
        'project_id',
        'last_contact_date',
        'notes',
        // Upwork fields
        'upwork_job_id',
        'upwork_job_url',
        'upwork_job_type',
        'upwork_budget_min',
        'upwork_budget_max',
        'upwork_proposals_count',
        'upwork_connects_required',
        'upwork_client_rating',
        'upwork_client_jobs_posted',
        'upwork_client_hourly_rate',
        'upwork_job_description',
        'upwork_skills_required',
        'upwork_experience_level',
        'upwork_job_duration',
        'upwork_job_status',
        'upwork_job_posted_at',
        'upwork_proposal_sent_at',
        'upwork_proposal_text',
        'upwork_proposal_amount',
        'upwork_proposal_delivery_days',
        // LinkedIn fields
        'linkedin_company_url',
        'linkedin_contact_url',
        'linkedin_connection_message',
        'linkedin_connection_sent_at',
        'linkedin_connection_status',
        // Bidding fields
        'bidding_platform',
        'bidding_status',
        'bidding_notes',
        'bidding_priority',
        'requires_connects',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'last_contact_date' => 'date',
        'upwork_budget_min' => 'decimal:2',
        'upwork_budget_max' => 'decimal:2',
        'upwork_client_hourly_rate' => 'decimal:2',
        'upwork_proposal_amount' => 'decimal:2',
        'upwork_job_posted_at' => 'datetime',
        'upwork_proposal_sent_at' => 'datetime',
        'linkedin_connection_sent_at' => 'datetime',
        'requires_connects' => 'boolean',
    ];

    /**
     * Get the user assigned to the lead.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the project for the lead.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
