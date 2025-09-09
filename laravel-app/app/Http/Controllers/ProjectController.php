<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['projectManager', 'client'])->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin can create projects
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only administrators can create projects.');
        }

        $users = User::all();
        return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create projects
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only administrators can create projects.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,completed,on_hold,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'project_manager_id' => 'required|exists:users,id',
            'client_id' => 'nullable|exists:users,id',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['projectManager', 'client', 'proposals', 'leads']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Only admin can edit projects
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only administrators can edit projects.');
        }

        $users = User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // Only admin can update projects
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only administrators can update projects.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,in_progress,completed,on_hold,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'project_manager_id' => 'required|exists:users,id',
            'client_id' => 'nullable|exists:users,id',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Only admin can delete projects
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Only administrators can delete projects.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
