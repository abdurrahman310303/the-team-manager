<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // BD users see only their leads, admin sees all leads
        if ($user->hasRole('admin')) {
            $leads = Lead::with(['assignedTo', 'project'])
                ->latest()
                ->paginate(15);
        } else {
            $leads = Lead::with(['assignedTo', 'project'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only BD and admin can create leads
        if (!auth()->user()->hasRole('bd') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only BD and admin can create leads.');
        }

        $projects = Project::where('status', 'in_progress')->get();
        $users = User::whereHas('role', function($query) {
            $query->whereIn('name', ['bd', 'admin']);
        })->get();

        return view('leads.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only BD and admin can create leads
        if (!auth()->user()->hasRole('bd') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only BD and admin can create leads.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiating,closed_won,closed_lost',
            'source' => 'required|in:website,referral,cold_call,social_media,advertisement,other',
            'estimated_value' => 'nullable|numeric|min:0',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'last_contact_date' => 'nullable|date',
            'notes' => 'nullable|string',
            // Bidding fields
            'bidding_platform' => 'required|in:upwork,linkedin,direct,referral,other',
            'bidding_status' => 'required|in:researching,ready_to_bid,bid_sent,interviewing,negotiating,won,lost,withdrawn',
            'bidding_priority' => 'nullable|integer|min:1|max:5',
            'bidding_notes' => 'nullable|string',
            'requires_connects' => 'boolean',
            // Upwork fields
            'upwork_job_id' => 'nullable|string|max:255',
            'upwork_job_url' => 'nullable|url|max:500',
            'upwork_job_type' => 'nullable|in:hourly,fixed_price,milestone',
            'upwork_budget_min' => 'nullable|numeric|min:0',
            'upwork_budget_max' => 'nullable|numeric|min:0',
            'upwork_connects_required' => 'nullable|integer|min:0',
            'upwork_client_rating' => 'nullable|in:excellent,good,average,poor,new',
            'upwork_experience_level' => 'nullable|in:entry,intermediate,expert',
            'upwork_job_duration' => 'nullable|integer|min:1',
            'upwork_job_description' => 'nullable|string',
            'upwork_skills_required' => 'nullable|string',
            'upwork_proposal_amount' => 'nullable|numeric|min:0',
            'upwork_proposal_delivery_days' => 'nullable|integer|min:1',
            'upwork_proposal_sent_at' => 'nullable|date',
            'upwork_proposal_text' => 'nullable|string',
            // LinkedIn fields
            'linkedin_company_url' => 'nullable|url|max:500',
            'linkedin_contact_url' => 'nullable|url|max:500',
            'linkedin_connection_message' => 'nullable|string',
            'linkedin_connection_sent_at' => 'nullable|date',
            'linkedin_connection_status' => 'nullable|in:pending,accepted,declined,ignored',
        ]);

        Lead::create($request->all());

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        // Check if user can view this lead
        if (!auth()->user()->hasRole('admin') && $lead->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $lead->load(['assignedTo', 'project']);
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        // Only BD and admin can edit leads
        if (!auth()->user()->hasRole('bd') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only BD and admin can edit leads.');
        }

        // Check if user can edit this lead
        if (!auth()->user()->hasRole('admin') && $lead->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $projects = Project::where('status', 'in_progress')->get();
        $users = User::whereHas('role', function($query) {
            $query->whereIn('name', ['bd', 'admin']);
        })->get();

        return view('leads.edit', compact('lead', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        // Only BD and admin can update leads
        if (!auth()->user()->hasRole('bd') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only BD and admin can update leads.');
        }

        // Check if user can update this lead
        if (!auth()->user()->hasRole('admin') && $lead->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal_sent,negotiating,closed_won,closed_lost',
            'source' => 'required|in:website,referral,cold_call,social_media,advertisement,other',
            'estimated_value' => 'nullable|numeric|min:0',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'last_contact_date' => 'nullable|date',
            'notes' => 'nullable|string',
            // Bidding fields
            'bidding_platform' => 'required|in:upwork,linkedin,direct,referral,other',
            'bidding_status' => 'required|in:researching,ready_to_bid,bid_sent,interviewing,negotiating,won,lost,withdrawn',
            'bidding_priority' => 'nullable|integer|min:1|max:5',
            'bidding_notes' => 'nullable|string',
            'requires_connects' => 'boolean',
            // Upwork fields
            'upwork_job_id' => 'nullable|string|max:255',
            'upwork_job_url' => 'nullable|url|max:500',
            'upwork_job_type' => 'nullable|in:hourly,fixed_price,milestone',
            'upwork_budget_min' => 'nullable|numeric|min:0',
            'upwork_budget_max' => 'nullable|numeric|min:0',
            'upwork_connects_required' => 'nullable|integer|min:0',
            'upwork_client_rating' => 'nullable|in:excellent,good,average,poor,new',
            'upwork_experience_level' => 'nullable|in:entry,intermediate,expert',
            'upwork_job_duration' => 'nullable|integer|min:1',
            'upwork_job_description' => 'nullable|string',
            'upwork_skills_required' => 'nullable|string',
            'upwork_proposal_amount' => 'nullable|numeric|min:0',
            'upwork_proposal_delivery_days' => 'nullable|integer|min:1',
            'upwork_proposal_sent_at' => 'nullable|date',
            'upwork_proposal_text' => 'nullable|string',
            // LinkedIn fields
            'linkedin_company_url' => 'nullable|url|max:500',
            'linkedin_contact_url' => 'nullable|url|max:500',
            'linkedin_connection_message' => 'nullable|string',
            'linkedin_connection_sent_at' => 'nullable|date',
            'linkedin_connection_status' => 'nullable|in:pending,accepted,declined,ignored',
        ]);

        $lead->update($request->all());

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        // Only BD and admin can delete leads
        if (!auth()->user()->hasRole('bd') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only BD and admin can delete leads.');
        }

        // Check if user can delete this lead
        if (!auth()->user()->hasRole('admin') && $lead->assigned_to !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
