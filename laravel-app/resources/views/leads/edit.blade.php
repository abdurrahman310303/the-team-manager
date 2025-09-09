<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Edit Lead') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('leads.show', $lead) }}" class="btn-secondary">
                    <i class="fas fa-eye mr-2"></i>
                    View Lead
                </a>
                <a href="{{ route('leads.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Leads
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('leads.update', $lead) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $lead->company_name) }}" 
                                    required class="form-input" placeholder="Enter company name">
                                @error('company_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $lead->contact_person) }}" 
                                    required class="form-input" placeholder="Enter contact person name">
                                @error('contact_person')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}" 
                                    required class="form-input" placeholder="Enter email address">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}" 
                                    class="form-input" placeholder="Enter phone number">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                    <option value="proposal_sent" {{ old('status', $lead->status) == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                                    <option value="negotiating" {{ old('status', $lead->status) == 'negotiating' ? 'selected' : '' }}>Negotiating</option>
                                    <option value="closed_won" {{ old('status', $lead->status) == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                    <option value="closed_lost" {{ old('status', $lead->status) == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="source" class="form-label">Source *</label>
                                <select name="source" id="source" required class="form-input">
                                    <option value="">Select source</option>
                                    <option value="website" {{ old('source', $lead->source) == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="cold_call" {{ old('source', $lead->source) == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                                    <option value="social_media" {{ old('source', $lead->source) == 'social_media' ? 'selected' : '' }}>Social Media</option>
                                    <option value="advertisement" {{ old('source', $lead->source) == 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                                    <option value="other" {{ old('source', $lead->source) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('source')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estimated_value" class="form-label">Estimated Value ($)</label>
                                <input type="number" name="estimated_value" id="estimated_value" value="{{ old('estimated_value', $lead->estimated_value) }}" 
                                    min="0" step="0.01" class="form-input" placeholder="Enter estimated value">
                                @error('estimated_value')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="assigned_to" class="form-label">Assigned To *</label>
                                <select name="assigned_to" id="assigned_to" required class="form-input">
                                    <option value="">Select user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role->display_name ?? 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="project_id" class="form-label">Related Project</label>
                                <select name="project_id" id="project_id" class="form-input">
                                    <option value="">Select project (optional)</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $lead->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ ucfirst(str_replace('_', ' ', $project->status)) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_contact_date" class="form-label">Last Contact Date</label>
                                <input type="date" name="last_contact_date" id="last_contact_date" value="{{ old('last_contact_date', $lead->last_contact_date?->format('Y-m-d')) }}" 
                                    class="form-input">
                                @error('last_contact_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-input" 
                                    placeholder="Enter lead description">{{ old('description', $lead->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-input" 
                                    placeholder="Enter additional notes">{{ old('notes', $lead->notes) }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Update Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
