<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'report_type',
        'report_date',
        'work_completed',
        'challenges_faced',
        'next_plans',
        'hours_worked',
        'leads_generated',
        'proposals_submitted',
        'projects_locked',
        'revenue_generated',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'revenue_generated' => 'decimal:2',
    ];

    /**
     * Get the user who created the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the report.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
