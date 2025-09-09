<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $expenses = Expense::with(['project', 'addedBy'])
                ->latest()
                ->paginate(10);
        } else {
            $expenses = $user->addedExpenses()
                ->with('project')
                ->latest()
                ->paginate(10);
        }
        
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('status', 'in_progress')->get();
        return view('expenses.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:development,marketing,infrastructure,tools,travel,other',
            'expense_date' => 'required|date',
            'receipt_url' => 'nullable|url',
            'notes' => 'nullable|string',
        ]);

        Expense::create([
            'project_id' => $request->project_id,
            'added_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'expense_date' => $request->expense_date,
            'receipt_url' => $request->receipt_url,
            'notes' => $request->notes,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['project', 'addedBy']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $projects = Project::where('status', 'in_progress')->get();
        return view('expenses.edit', compact('expense', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:development,marketing,infrastructure,tools,travel,other',
            'expense_date' => 'required|date',
            'receipt_url' => 'nullable|url',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Approve an expense (Admin only).
     */
    public function approve(Expense $expense)
    {
        $expense->update(['status' => 'approved']);
        
        return redirect()->back()
            ->with('success', 'Expense approved successfully.');
    }

    /**
     * Reject an expense (Admin only).
     */
    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);
        
        return redirect()->back()
            ->with('success', 'Expense rejected successfully.');
    }
}
