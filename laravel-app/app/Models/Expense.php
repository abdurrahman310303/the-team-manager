<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'added_by',
        'title',
        'description',
        'amount',
        'category',
        'expense_date',
        'receipt_url',
        'status',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the project that owns the expense.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who added the expense.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
