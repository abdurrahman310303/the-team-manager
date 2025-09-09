<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Record Payment') }}
            </h2>
            <a href="{{ route('payments.index') }}" class="btn-secondary">
                Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">Payment Details</h3>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-error mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="form-label">Project (Optional)</label>
                                <select name="project_id" id="project_id" class="form-input">
                                    <option value="">Select a project (if applicable)</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ ucfirst(str_replace('_', ' ', $project->status)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="fund_purpose" class="form-label">Fund Purpose *</label>
                                <select name="fund_purpose" id="fund_purpose" required class="form-input">
                                    <option value="">Select fund purpose</option>
                                    <option value="salaries" {{ old('fund_purpose') == 'salaries' ? 'selected' : '' }}>Team Salaries</option>
                                    <option value="upwork_connects" {{ old('fund_purpose') == 'upwork_connects' ? 'selected' : '' }}>Upwork Connects</option>
                                    <option value="project_expenses" {{ old('fund_purpose') == 'project_expenses' ? 'selected' : '' }}>Project Expenses</option>
                                    <option value="office_rent" {{ old('fund_purpose') == 'office_rent' ? 'selected' : '' }}>Office Rent</option>
                                    <option value="equipment" {{ old('fund_purpose') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                    <option value="marketing" {{ old('fund_purpose') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="other" {{ old('fund_purpose') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="amount" class="form-label">Amount *</label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                                    required min="0" step="0.01" class="form-input" 
                                    placeholder="Enter payment amount">
                            </div>

                            <div>
                                <label for="currency" class="form-label">Currency *</label>
                                <select name="currency" id="currency" required class="form-input">
                                    <option value="">Select currency</option>
                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="CAD" {{ old('currency') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                    <option value="AUD" {{ old('currency') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                    <option value="JPY" {{ old('currency') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                    <option value="PKR" {{ old('currency') == 'PKR' ? 'selected' : '' }}>PKR - Pakistani Rupee</option>
                                    <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                </select>
                            </div>

                            <div>
                                <label for="exchange_rate" class="form-label">Exchange Rate to USD</label>
                                <input type="number" name="exchange_rate" id="exchange_rate" value="{{ old('exchange_rate') }}" 
                                    min="0" step="0.0001" class="form-input" 
                                    placeholder="Enter exchange rate (e.g., 0.85 for EUR)">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if currency is USD or if you don't know the rate</p>
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_project_related" id="is_project_related" value="1" 
                                        {{ old('is_project_related') ? 'checked' : '' }} class="mr-2">
                                    <label for="is_project_related" class="text-sm text-gray-700">This payment is related to a specific project</label>
                                </div>
                            </div>

                            <div>
                                <label for="payment_type" class="form-label">Payment Type *</label>
                                <select name="payment_type" id="payment_type" required class="form-input">
                                    <option value="">Select payment type</option>
                                    <option value="investment" {{ old('payment_type') == 'investment' ? 'selected' : '' }}>Investment</option>
                                    <option value="expense_reimbursement" {{ old('payment_type') == 'expense_reimbursement' ? 'selected' : '' }}>Expense Reimbursement</option>
                                    <option value="profit_share" {{ old('payment_type') == 'profit_share' ? 'selected' : '' }}>Profit Share</option>
                                    <option value="reimbursement" {{ old('payment_type') == 'reimbursement' ? 'selected' : '' }}>Reimbursement</option>
                                    <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select name="payment_method" id="payment_method" required class="form-input">
                                    <option value="">Select payment method</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_date" class="form-label">Payment Date *</label>
                                <input type="date" name="payment_date" id="payment_date" 
                                    value="{{ old('payment_date', date('Y-m-d')) }}" required class="form-input">
                            </div>

                            <div>
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" name="reference_number" id="reference_number" 
                                    value="{{ old('reference_number') }}" class="form-input" 
                                    placeholder="Enter reference number">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="4" 
                                    class="form-input" placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('payments.index') }}" class="btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
