<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'proposed_budget',
        'status',
        'project_id',
        'submitted_by',
        'client_id',
        'submission_date',
        'review_date',
        'notes',
    ];

    protected $casts = [
        'proposed_budget' => 'decimal:2',
        'submission_date' => 'date',
        'review_date' => 'date',
    ];

    /**
     * Get the project that owns the proposal.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who submitted the proposal.
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the client for the proposal.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
