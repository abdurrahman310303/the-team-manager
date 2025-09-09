<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Investors can see all payments, others see only their own
        if ($user->hasRole('investor')) {
            $payments = Payment::with(['project', 'investor'])
                ->latest()
                ->paginate(15);
        } else {
            $payments = Payment::with(['project', 'investor'])
                ->where('investor_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::whereIn('status', ['in_progress', 'completed'])->get();
        return view('payments.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'exchange_rate' => 'nullable|numeric|min:0',
            'payment_type' => 'required|in:investment,expense_reimbursement,profit_share,reimbursement,other',
            'fund_purpose' => 'required|in:salaries,upwork_connects,project_expenses,office_rent,equipment,marketing,other',
            'is_project_related' => 'boolean',
            'payment_method' => 'required|in:bank_transfer,check,cash,other',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate USD amount if exchange rate is provided
        $amountUsd = null;
        if ($request->exchange_rate && $request->currency !== 'USD') {
            $amountUsd = $request->amount * $request->exchange_rate;
        } elseif ($request->currency === 'USD') {
            $amountUsd = $request->amount;
        }

        Payment::create([
            'project_id' => $request->project_id,
            'investor_id' => Auth::id(),
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'exchange_rate' => $request->exchange_rate,
            'amount_usd' => $amountUsd,
            'payment_type' => $request->payment_type,
            'fund_purpose' => $request->fund_purpose,
            'is_project_related' => $request->has('is_project_related'),
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $user = Auth::user();
        
        // Users can only view their own payments unless they're admin
        if (!$user->hasRole('admin') && $payment->investor_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['project', 'investor']);
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        // Only admin and the payment owner can edit
        if (!Auth::user()->hasRole('admin') && $payment->investor_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $projects = Project::whereIn('status', ['in_progress', 'completed'])->get();
        
        return view('payments.edit', compact('payment', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Only admin and the payment owner can update
        if (!Auth::user()->hasRole('admin') && $payment->investor_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:investment,expense_reimbursement,profit_share,reimbursement,other',
            'payment_method' => 'required|in:bank_transfer,check,cash,other',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only admin and the payment owner can delete
        if (!Auth::user()->hasRole('admin') && $payment->investor_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
