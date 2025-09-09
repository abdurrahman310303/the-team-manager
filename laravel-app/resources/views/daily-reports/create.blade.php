<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Daily Report') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">Daily Work Report</h3>
                        <a href="{{ route('daily-reports.index') }}" class="btn-secondary">
                            Back to Reports
                        </a>
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

                    <form action="{{ route('daily-reports.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="form-label">Project (Optional)</label>
                                <select name="project_id" id="project_id" class="form-input">
                                    <option value="">Select a project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="report_date" class="form-label">Report Date</label>
                                <input type="date" name="report_date" id="report_date" value="{{ old('report_date', date('Y-m-d')) }}" required
                                    class="form-input @error('report_date') border-red-500 @enderror">
                                @error('report_date')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="work_completed" class="form-label">Work Completed Today *</label>
                                <textarea name="work_completed" id="work_completed" rows="4" required
                                    class="form-input"
                                    placeholder="Describe what you accomplished today...">{{ old('work_completed') }}</textarea>
                            </div>

                            <div>
                                <label for="challenges_faced" class="form-label">Challenges Faced</label>
                                <textarea name="challenges_faced" id="challenges_faced" rows="3"
                                    class="form-input"
                                    placeholder="Any challenges or issues you encountered...">{{ old('challenges_faced') }}</textarea>
                            </div>

                            <div>
                                <label for="next_plans" class="form-label">Next Plans</label>
                                <textarea name="next_plans" id="next_plans" rows="3"
                                    class="form-input"
                                    placeholder="What do you plan to work on next...">{{ old('next_plans') }}</textarea>
                            </div>

                            <div>
                                <label for="hours_worked" class="form-label">Hours Worked *</label>
                                <input type="number" name="hours_worked" id="hours_worked" value="{{ old('hours_worked') }}" min="0" max="24" required
                                    class="form-input">
                            </div>

                            @if($reportType === 'bd')
                            <div>
                                <label for="leads_generated" class="form-label">Leads Generated</label>
                                <input type="number" name="leads_generated" id="leads_generated" value="{{ old('leads_generated', 0) }}" min="0"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="proposals_submitted" class="form-label">Proposals Submitted</label>
                                <input type="number" name="proposals_submitted" id="proposals_submitted" value="{{ old('proposals_submitted', 0) }}" min="0"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="projects_locked" class="form-label">Projects Locked</label>
                                <input type="number" name="projects_locked" id="projects_locked" value="{{ old('projects_locked', 0) }}" min="0"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="revenue_generated" class="form-label">Revenue Generated ($)</label>
                                <input type="number" name="revenue_generated" id="revenue_generated" value="{{ old('revenue_generated', 0) }}" min="0" step="0.01"
                                    class="form-input">
                            </div>
                            @endif

                            <div class="md:col-span-2">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="form-input"
                                    placeholder="Any additional notes or comments...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('daily-reports.index') }}" class="btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
