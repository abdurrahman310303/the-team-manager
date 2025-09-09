<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'investor_id',
        'amount',
        'currency',
        'exchange_rate',
        'amount_usd',
        'payment_type',
        'fund_purpose',
        'is_project_related',
        'payment_method',
        'payment_date',
        'reference_number',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'amount_usd' => 'decimal:2',
        'is_project_related' => 'boolean',
    ];

    /**
     * Get the project that owns the payment.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the investor who made the payment.
     */
    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }
}
