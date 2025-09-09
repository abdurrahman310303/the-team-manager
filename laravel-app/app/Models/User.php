<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the projects managed by the user.
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    /**
     * Get the projects where user is a client.
     */
    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Get the proposals submitted by the user.
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'submitted_by');
    }

    /**
     * Get the leads assigned to the user.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    /**
     * Get the daily reports for the user.
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    /**
     * Get the expenses added by the user.
     */
    public function addedExpenses()
    {
        return $this->hasMany(Expense::class, 'added_by');
    }

    /**
     * Get the payments made by the user (as investor).
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'investor_id');
    }

    /**
     * Get the profit shares for the user.
     */
    public function profitShares()
    {
        return $this->hasMany(ProfitShare::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }
}
