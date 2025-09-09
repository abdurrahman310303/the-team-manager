<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Create Profit Share') }}
            </h2>
            <a href="{{ route('profit-shares.index') }}" class="btn-secondary">
                Back to Profit Shares
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">Profit Share Details</h3>
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

                    <form action="{{ route('profit-shares.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="form-label">Project *</label>
                                <select name="project_id" id="project_id" required class="form-input">
                                    <option value="">Select a completed project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} (Budget: ${{ number_format($project->budget, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="user_id" class="form-label">User *</label>
                                <select name="user_id" id="user_id" required class="form-input">
                                    <option value="">Select a user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role ? $user->role->display_name : 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="percentage" class="form-label">Percentage (%) *</label>
                                <input type="number" name="percentage" id="percentage" value="{{ old('percentage') }}" 
                                    required min="0" max="100" step="0.01" class="form-input" 
                                    placeholder="Enter percentage (e.g., 25.50)">
                            </div>

                            <div>
                                <label for="amount" class="form-label">Amount ($) *</label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                                    required min="0" step="0.01" class="form-input" 
                                    placeholder="Enter amount">
                            </div>

                            <div>
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="calculated_date" class="form-label">Calculated Date *</label>
                                <input type="date" name="calculated_date" id="calculated_date" 
                                    value="{{ old('calculated_date', date('Y-m-d')) }}" required class="form-input">
                            </div>

                            <div>
                                <label for="paid_date" class="form-label">Paid Date</label>
                                <input type="date" name="paid_date" id="paid_date" 
                                    value="{{ old('paid_date') }}" class="form-input">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="4" 
                                    class="form-input" placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('profit-shares.index') }}" class="btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Create Profit Share
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
