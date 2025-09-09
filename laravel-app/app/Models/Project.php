<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'budget',
        'start_date',
        'end_date',
        'project_manager_id',
        'client_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the project manager for the project.
     */
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    /**
     * Get the client for the project.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the proposals for the project.
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    /**
     * Get the leads for the project.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the daily reports for the project.
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * Get the expenses for the project.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the payments for the project.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the profit shares for the project.
     */
    public function profitShares()
    {
        return $this->hasMany(ProfitShare::class);
    }

    /**
     * Calculate total expenses for the project.
     */
    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->where('status', 'approved')->sum('amount');
    }

    /**
     * Calculate total payments for the project.
     */
    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Calculate net profit for the project.
     */
    public function getNetProfitAttribute()
    {
        return $this->total_payments - $this->total_expenses;
    }
}
