<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Create New Lead') }}
            </h2>
            <a href="{{ route('leads.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Leads
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('leads.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Platform Selection -->
                <div class="dashboard-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fas fa-bullseye mr-2"></i>
                            Platform & Bidding Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="bidding_platform" class="form-label">Platform *</label>
                                <select name="bidding_platform" id="bidding_platform" required class="form-input" onchange="togglePlatformFields()">
                                    <option value="">Select platform</option>
                                    <option value="upwork" {{ old('bidding_platform') == 'upwork' ? 'selected' : '' }}>Upwork</option>
                                    <option value="linkedin" {{ old('bidding_platform') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="direct" {{ old('bidding_platform') == 'direct' ? 'selected' : '' }}>Direct Contact</option>
                                    <option value="referral" {{ old('bidding_platform') == 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="other" {{ old('bidding_platform') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('bidding_platform')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bidding_status" class="form-label">Bidding Status *</label>
                                <select name="bidding_status" id="bidding_status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="researching" {{ old('bidding_status') == 'researching' ? 'selected' : '' }}>Researching</option>
                                    <option value="ready_to_bid" {{ old('bidding_status') == 'ready_to_bid' ? 'selected' : '' }}>Ready to Bid</option>
                                    <option value="bid_sent" {{ old('bidding_status') == 'bid_sent' ? 'selected' : '' }}>Bid Sent</option>
                                    <option value="interviewing" {{ old('bidding_status') == 'interviewing' ? 'selected' : '' }}>Interviewing</option>
                                    <option value="negotiating" {{ old('bidding_status') == 'negotiating' ? 'selected' : '' }}>Negotiating</option>
                                    <option value="won" {{ old('bidding_status') == 'won' ? 'selected' : '' }}>Won</option>
                                    <option value="lost" {{ old('bidding_status') == 'lost' ? 'selected' : '' }}>Lost</option>
                                    <option value="withdrawn" {{ old('bidding_status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                </select>
                                @error('bidding_status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bidding_priority" class="form-label">Priority (1-5)</label>
                                <select name="bidding_priority" id="bidding_priority" class="form-input">
                                    <option value="1" {{ old('bidding_priority') == '1' ? 'selected' : '' }}>1 - Low</option>
                                    <option value="2" {{ old('bidding_priority') == '2' ? 'selected' : '' }}>2 - Below Average</option>
                                    <option value="3" {{ old('bidding_priority') == '3' ? 'selected' : '' }}>3 - Average</option>
                                    <option value="4" {{ old('bidding_priority') == '4' ? 'selected' : '' }}>4 - High</option>
                                    <option value="5" {{ old('bidding_priority') == '5' ? 'selected' : '' }}>5 - Critical</option>
                                </select>
                                @error('bidding_priority')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Lead Information -->
                <div class="dashboard-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            Basic Lead Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" 
                                    required class="form-input" placeholder="Enter company name">
                                @error('company_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" 
                                    required class="form-input" placeholder="Enter contact person name">
                                @error('contact_person')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                    required class="form-input" placeholder="Enter email address">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                    class="form-input" placeholder="Enter phone number">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="estimated_value" class="form-label">Estimated Value ($)</label>
                                <input type="number" name="estimated_value" id="estimated_value" value="{{ old('estimated_value') }}" 
                                    min="0" step="0.01" class="form-input" placeholder="Enter estimated value">
                                @error('estimated_value')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="last_contact_date" class="form-label">Last Contact Date</label>
                                <input type="date" name="last_contact_date" id="last_contact_date" value="{{ old('last_contact_date') }}" 
                                    class="form-input">
                                @error('last_contact_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-input" 
                                placeholder="Enter lead description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Upwork Specific Fields -->
                <div id="upwork-fields" class="dashboard-card" style="display: none;">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fab fa-upwork mr-2"></i>
                            Upwork Job Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="upwork_job_id" class="form-label">Job ID</label>
                                <input type="text" name="upwork_job_id" id="upwork_job_id" value="{{ old('upwork_job_id') }}" 
                                    class="form-input" placeholder="e.g., ~1234567890abcdef">
                                @error('upwork_job_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_job_url" class="form-label">Job URL</label>
                                <input type="url" name="upwork_job_url" id="upwork_job_url" value="{{ old('upwork_job_url') }}" 
                                    class="form-input" placeholder="https://upwork.com/jobs/...">
                                @error('upwork_job_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_job_type" class="form-label">Job Type</label>
                                <select name="upwork_job_type" id="upwork_job_type" class="form-input">
                                    <option value="">Select type</option>
                                    <option value="hourly" {{ old('upwork_job_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    <option value="fixed_price" {{ old('upwork_job_type') == 'fixed_price' ? 'selected' : '' }}>Fixed Price</option>
                                    <option value="milestone" {{ old('upwork_job_type') == 'milestone' ? 'selected' : '' }}>Milestone</option>
                                </select>
                                @error('upwork_job_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_budget_min" class="form-label">Min Budget ($)</label>
                                <input type="number" name="upwork_budget_min" id="upwork_budget_min" value="{{ old('upwork_budget_min') }}" 
                                    min="0" step="0.01" class="form-input" placeholder="Minimum budget">
                                @error('upwork_budget_min')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_budget_max" class="form-label">Max Budget ($)</label>
                                <input type="number" name="upwork_budget_max" id="upwork_budget_max" value="{{ old('upwork_budget_max') }}" 
                                    min="0" step="0.01" class="form-input" placeholder="Maximum budget">
                                @error('upwork_budget_max')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_connects_required" class="form-label">Connects Required</label>
                                <input type="number" name="upwork_connects_required" id="upwork_connects_required" value="{{ old('upwork_connects_required') }}" 
                                    min="0" class="form-input" placeholder="Number of connects">
                                @error('upwork_connects_required')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_client_rating" class="form-label">Client Rating</label>
                                <select name="upwork_client_rating" id="upwork_client_rating" class="form-input">
                                    <option value="">Select rating</option>
                                    <option value="excellent" {{ old('upwork_client_rating') == 'excellent' ? 'selected' : '' }}>Excellent (4.5+)</option>
                                    <option value="good" {{ old('upwork_client_rating') == 'good' ? 'selected' : '' }}>Good (4.0-4.4)</option>
                                    <option value="average" {{ old('upwork_client_rating') == 'average' ? 'selected' : '' }}>Average (3.5-3.9)</option>
                                    <option value="poor" {{ old('upwork_client_rating') == 'poor' ? 'selected' : '' }}>Poor (<3.5)</option>
                                    <option value="new" {{ old('upwork_client_rating') == 'new' ? 'selected' : '' }}>New Client</option>
                                </select>
                                @error('upwork_client_rating')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_experience_level" class="form-label">Experience Level</label>
                                <select name="upwork_experience_level" id="upwork_experience_level" class="form-input">
                                    <option value="">Select level</option>
                                    <option value="entry" {{ old('upwork_experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="intermediate" {{ old('upwork_experience_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="expert" {{ old('upwork_experience_level') == 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                                @error('upwork_experience_level')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_job_duration" class="form-label">Duration (days)</label>
                                <input type="number" name="upwork_job_duration" id="upwork_job_duration" value="{{ old('upwork_job_duration') }}" 
                                    min="1" class="form-input" placeholder="Project duration">
                                @error('upwork_job_duration')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="upwork_job_description" class="form-label">Job Description</label>
                            <textarea name="upwork_job_description" id="upwork_job_description" rows="4" class="form-input" 
                                placeholder="Copy the job description from Upwork">{{ old('upwork_job_description') }}</textarea>
                            @error('upwork_job_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="upwork_skills_required" class="form-label">Skills Required</label>
                            <textarea name="upwork_skills_required" id="upwork_skills_required" rows="2" class="form-input" 
                                placeholder="List required skills (comma separated)">{{ old('upwork_skills_required') }}</textarea>
                            @error('upwork_skills_required')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- LinkedIn Specific Fields -->
                <div id="linkedin-fields" class="dashboard-card" style="display: none;">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fab fa-linkedin mr-2"></i>
                            LinkedIn Connection Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="linkedin_company_url" class="form-label">Company LinkedIn URL</label>
                                <input type="url" name="linkedin_company_url" id="linkedin_company_url" value="{{ old('linkedin_company_url') }}" 
                                    class="form-input" placeholder="https://linkedin.com/company/...">
                                @error('linkedin_company_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="linkedin_contact_url" class="form-label">Contact LinkedIn URL</label>
                                <input type="url" name="linkedin_contact_url" id="linkedin_contact_url" value="{{ old('linkedin_contact_url') }}" 
                                    class="form-input" placeholder="https://linkedin.com/in/...">
                                @error('linkedin_contact_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="linkedin_connection_message" class="form-label">Connection Message</label>
                            <textarea name="linkedin_connection_message" id="linkedin_connection_message" rows="3" class="form-input" 
                                placeholder="Your LinkedIn connection message">{{ old('linkedin_connection_message') }}</textarea>
                            @error('linkedin_connection_message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Proposal Information -->
                <div class="dashboard-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Proposal & Bidding Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="upwork_proposal_amount" class="form-label">Proposal Amount ($)</label>
                                <input type="number" name="upwork_proposal_amount" id="upwork_proposal_amount" value="{{ old('upwork_proposal_amount') }}" 
                                    min="0" step="0.01" class="form-input" placeholder="Your bid amount">
                                @error('upwork_proposal_amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_proposal_delivery_days" class="form-label">Delivery Time (days)</label>
                                <input type="number" name="upwork_proposal_delivery_days" id="upwork_proposal_delivery_days" value="{{ old('upwork_proposal_delivery_days') }}" 
                                    min="1" class="form-input" placeholder="Days to complete">
                                @error('upwork_proposal_delivery_days')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="upwork_proposal_sent_at" class="form-label">Proposal Sent Date</label>
                                <input type="datetime-local" name="upwork_proposal_sent_at" id="upwork_proposal_sent_at" value="{{ old('upwork_proposal_sent_at') }}" 
                                    class="form-input">
                                @error('upwork_proposal_sent_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="upwork_proposal_text" class="form-label">Proposal Text</label>
                            <textarea name="upwork_proposal_text" id="upwork_proposal_text" rows="6" class="form-input" 
                                placeholder="Your proposal text">{{ old('upwork_proposal_text') }}</textarea>
                            @error('upwork_proposal_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="dashboard-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="assigned_to" class="form-label">Assigned To *</label>
                                <select name="assigned_to" id="assigned_to" required class="form-input">
                                    <option value="">Select user</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ ucfirst(str_replace('_', ' ', $project->status)) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="bidding_notes" class="form-label">Bidding Notes</label>
                            <textarea name="bidding_notes" id="bidding_notes" rows="4" class="form-input" 
                                placeholder="Additional notes about this lead">{{ old('bidding_notes') }}</textarea>
                            @error('bidding_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4 flex items-center">
                            <input type="checkbox" name="requires_connects" id="requires_connects" value="1" 
                                {{ old('requires_connects') ? 'checked' : '' }} class="mr-2">
                            <label for="requires_connects" class="text-sm text-gray-700">This lead requires Upwork connects</label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Create Lead
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePlatformFields() {
            const platform = document.getElementById('bidding_platform').value;
            const upworkFields = document.getElementById('upwork-fields');
            const linkedinFields = document.getElementById('linkedin-fields');
            
            // Hide all platform-specific fields
            upworkFields.style.display = 'none';
            linkedinFields.style.display = 'none';
            
            // Show relevant fields based on platform
            if (platform === 'upwork') {
                upworkFields.style.display = 'block';
            } else if (platform === 'linkedin') {
                linkedinFields.style.display = 'block';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePlatformFields();
        });
    </script>
</x-app-layout>
