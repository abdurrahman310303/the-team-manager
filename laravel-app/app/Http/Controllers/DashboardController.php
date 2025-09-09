<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name ?? 'guest';

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'developer':
                return $this->developerDashboard();
            case 'investor':
                return $this->investorDashboard();
            case 'bd':
                return $this->bdDashboard();
            default:
                return $this->guestDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'in_progress')->count(),
            'total_proposals' => Proposal::count(),
            'pending_proposals' => Proposal::where('status', 'under_review')->count(),
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_users' => User::count(),
            'total_budget' => Project::sum('budget'),
        ];

        $recent_projects = Project::with(['projectManager', 'client'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_proposals = Proposal::with(['project', 'submittedBy'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_leads = Lead::with(['assignedTo', 'project'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_projects', 'recent_proposals', 'recent_leads'));
    }

    private function developerDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'my_projects' => $user->managedProjects()->count(),
            'active_projects' => $user->managedProjects()->where('status', 'in_progress')->count(),
            'completed_projects' => $user->managedProjects()->where('status', 'completed')->count(),
            'my_proposals' => $user->proposals()->count(),
        ];

        $my_projects = $user->managedProjects()
            ->with(['client'])
            ->latest()
            ->limit(5)
            ->get();

        $my_proposals = $user->proposals()
            ->with(['project'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.developer', compact('stats', 'my_projects', 'my_proposals'));
    }

    private function investorDashboard()
    {
        $user = Auth::user();
        
        // Get payment statistics
        $total_payments = $user->payments()->sum('amount_usd') ?? 0;
        $total_payments_count = $user->payments()->count();
        $pending_payments = $user->payments()->where('status', 'pending')->sum('amount_usd') ?? 0;
        $completed_payments = $user->payments()->where('status', 'completed')->sum('amount_usd') ?? 0;
        
        // Get payments by fund purpose
        $payments_by_purpose = $user->payments()
            ->selectRaw('fund_purpose, count(*) as count, sum(amount_usd) as total_amount')
            ->groupBy('fund_purpose')
            ->get();
            
        // Get payments by currency
        $payments_by_currency = $user->payments()
            ->selectRaw('currency, count(*) as count, sum(amount) as total_amount')
            ->groupBy('currency')
            ->get();

        $stats = [
            'total_investment' => $total_payments,
            'active_investments' => $pending_payments,
            'completed_investments' => $completed_payments,
            'roi_projects' => Project::where('status', 'completed')->count(),
            'total_payments_count' => $total_payments_count,
            'payments_by_purpose' => $payments_by_purpose,
            'payments_by_currency' => $payments_by_currency,
        ];

        $projects_by_status = Project::selectRaw('status, count(*) as count, sum(budget) as total_budget')
            ->groupBy('status')
            ->get();

        $recent_projects = Project::with(['projectManager', 'client'])
            ->latest()
            ->limit(10)
            ->get();
            
        $recent_payments = $user->payments()
            ->with(['project'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.investor', compact('stats', 'projects_by_status', 'recent_projects', 'recent_payments'));
    }

    private function bdDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'my_leads' => $user->leads()->count(),
            'new_leads' => $user->leads()->where('status', 'new')->count(),
            'qualified_leads' => $user->leads()->where('status', 'qualified')->count(),
            'converted_leads' => $user->leads()->where('status', 'closed_won')->count(),
            'total_value' => $user->leads()->sum('estimated_value'),
        ];

        $leads_by_status = $user->leads()
            ->selectRaw('status, count(*) as count, sum(estimated_value) as total_value')
            ->groupBy('status')
            ->get();

        $my_leads = $user->leads()
            ->with(['project'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.bd', compact('stats', 'leads_by_status', 'my_leads'));
    }

    private function guestDashboard()
    {
        return view('dashboard.guest');
    }

    public function adminOverview()
    {
        $stats = [
            'total_users' => User::count(),
            'users_by_role' => User::with('role')
                ->get()
                ->groupBy(function($user) {
                    return $user->role ? $user->role->name : 'no_role';
                })
                ->map->count(),
            'total_projects' => Project::count(),
            'projects_by_status' => Project::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'total_proposals' => Proposal::count(),
            'proposals_by_status' => Proposal::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'total_leads' => Lead::count(),
            'leads_by_status' => Lead::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        $users = User::with(['role', 'managedProjects', 'proposals', 'leads'])->get();
        
        // Get recent projects
        $recent_projects = Project::with(['projectManager', 'client'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent proposals
        $recent_proposals = Proposal::with(['project', 'submittedBy'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent leads
        $recent_leads = Lead::with(['assignedTo', 'project'])
            ->latest()
            ->limit(5)
            ->get();

        // Create recent activities collection
        $recent_activities = collect();
        
        // Add recent projects to activities
        foreach ($recent_projects as $project) {
            $recent_activities->push([
                'type' => 'project',
                'title' => $project->name,
                'description' => 'Status: ' . ucfirst(str_replace('_', ' ', $project->status)),
                'time' => $project->created_at->diffForHumans(),
            ]);
        }
        
        // Add recent proposals to activities
        foreach ($recent_proposals as $proposal) {
            $recent_activities->push([
                'type' => 'proposal',
                'title' => $proposal->title,
                'description' => 'Status: ' . ucfirst(str_replace('_', ' ', $proposal->status)),
                'time' => $proposal->created_at->diffForHumans(),
            ]);
        }

        return view('admin.overview', compact('stats', 'users', 'recent_activities'));
    }
}
