<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfitShare;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfitShareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin can see all profit shares, others see only their own
        if ($user->hasRole('admin')) {
            $profitShares = ProfitShare::with(['project', 'user.role'])
                ->latest()
                ->paginate(15);
        } else {
            $profitShares = ProfitShare::with(['project', 'user.role'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('profit-shares.index', compact('profitShares'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin can create profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $projects = Project::where('status', 'completed')->get();
        $users = User::with('role')->get();
        
        return view('profit-shares.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
            'calculated_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:calculated_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        ProfitShare::create($request->all());

        return redirect()->route('profit-shares.index')
            ->with('success', 'Profit share created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfitShare $profitShare)
    {
        $user = Auth::user();
        
        // Users can only view their own profit shares unless they're admin
        if (!$user->hasRole('admin') && $profitShare->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $profitShare->load(['project', 'user.role']);
        
        return view('profit-shares.show', compact('profitShare'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfitShare $profitShare)
    {
        // Only admin can edit profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $projects = Project::where('status', 'completed')->get();
        $users = User::with('role')->get();
        
        return view('profit-shares.edit', compact('profitShare', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfitShare $profitShare)
    {
        // Only admin can update profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
            'calculated_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:calculated_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $profitShare->update($request->all());

        return redirect()->route('profit-shares.index')
            ->with('success', 'Profit share updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfitShare $profitShare)
    {
        // Only admin can delete profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $profitShare->delete();

        return redirect()->route('profit-shares.index')
            ->with('success', 'Profit share deleted successfully.');
    }

    /**
     * Calculate profit shares for a project.
     */
    public function calculate(Request $request)
    {
        // Only admin can calculate profit shares
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'total_profit' => 'required|numeric|min:0',
        ]);

        $project = Project::findOrFail($request->project_id);
        $totalProfit = $request->total_profit;
        
        // Define profit sharing percentages for different roles
        $rolePercentages = [
            'admin' => 30.0,
            'developer' => 25.0,
            'bd' => 20.0,
            'investor' => 25.0
        ];

        $users = User::with('role')->get();
        $createdShares = [];

        foreach ($users as $user) {
            if ($user->role) {
                $percentage = $rolePercentages[$user->role->name] ?? 0;
                $amount = ($totalProfit * $percentage) / 100;

                $profitShare = ProfitShare::create([
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'status' => 'pending',
                    'calculated_date' => now()->toDateString(),
                    'notes' => 'Auto-calculated profit share for ' . $project->name,
                ]);

                $createdShares[] = $profitShare;
            }
        }

        return redirect()->route('profit-shares.index')
            ->with('success', 'Profit shares calculated and created successfully for ' . count($createdShares) . ' users.');
    }
}
