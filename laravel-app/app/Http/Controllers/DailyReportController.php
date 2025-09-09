<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $reports = $user->dailyReports()
            ->with('project')
            ->latest('report_date')
            ->paginate(10);
        
        return view('daily-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $projects = $user->managedProjects()->get();
        $reportType = $user->role->name === 'bd' ? 'bd' : 'developer';
        
        return view('daily-reports.create', compact('projects', 'reportType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'report_date' => 'required|date',
            'work_completed' => 'required|string',
            'challenges_faced' => 'nullable|string',
            'next_plans' => 'nullable|string',
            'hours_worked' => 'required|integer|min:0|max:24',
            'leads_generated' => 'integer|min:0',
            'proposals_submitted' => 'integer|min:0',
            'projects_locked' => 'integer|min:0',
            'revenue_generated' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $reportType = $user->role->name === 'bd' ? 'bd' : 'developer';
        
        // Check if a report already exists for this user, date, and type
        $existingReport = DailyReport::where('user_id', $user->id)
            ->where('report_date', $request->report_date)
            ->where('report_type', $reportType)
            ->first();
            
        if ($existingReport) {
            return redirect()->back()
                ->withErrors(['report_date' => 'A daily report already exists for this date and type. Please edit the existing report instead.'])
                ->withInput();
        }
        
        DailyReport::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'report_type' => $reportType,
            'report_date' => $request->report_date,
            'work_completed' => $request->work_completed,
            'challenges_faced' => $request->challenges_faced,
            'next_plans' => $request->next_plans,
            'hours_worked' => $request->hours_worked,
            'leads_generated' => $request->leads_generated ?? 0,
            'proposals_submitted' => $request->proposals_submitted ?? 0,
            'projects_locked' => $request->projects_locked ?? 0,
            'revenue_generated' => $request->revenue_generated ?? 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Daily report submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load(['user', 'project']);
        return view('daily-reports.show', compact('dailyReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $dailyReport)
    {
        $user = Auth::user();
        $projects = $user->managedProjects()->get();
        
        return view('daily-reports.edit', compact('dailyReport', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'report_date' => 'required|date',
            'work_completed' => 'required|string',
            'challenges_faced' => 'nullable|string',
            'next_plans' => 'nullable|string',
            'hours_worked' => 'required|integer|min:0|max:24',
            'leads_generated' => 'integer|min:0',
            'proposals_submitted' => 'integer|min:0',
            'projects_locked' => 'integer|min:0',
            'revenue_generated' => 'numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $dailyReport->update($request->all());

        return redirect()->route('daily-reports.index')
            ->with('success', 'Daily report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        $dailyReport->delete();

        return redirect()->route('daily-reports.index')
            ->with('success', 'Daily report deleted successfully.');
    }
}
