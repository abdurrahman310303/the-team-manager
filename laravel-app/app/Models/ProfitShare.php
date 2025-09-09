<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfitShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'percentage',
        'amount',
        'status',
        'calculated_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'calculated_date' => 'date',
        'paid_date' => 'date',
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the project that owns the profit share.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who receives the profit share.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
