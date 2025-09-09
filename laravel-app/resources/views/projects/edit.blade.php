<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Edit Project') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.index') }}" class="btn-secondary">
                    Back to Projects
                </a>
                <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
                    View Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">Edit Project: {{ $project->name }}</h3>
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

                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="form-label">Project Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required
                                    class="form-input" placeholder="Enter project name">
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-input" placeholder="Enter project description">{{ old('description', $project->description) }}</textarea>
                            </div>

                            <div>
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="budget" class="form-label">Budget ($)</label>
                                <input type="number" name="budget" id="budget" value="{{ old('budget', $project->budget) }}" min="0" step="0.01"
                                    class="form-input" placeholder="Enter project budget">
                            </div>

                            <div>
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="project_manager_id" class="form-label">Project Manager *</label>
                                <select name="project_manager_id" id="project_manager_id" required class="form-input">
                                    <option value="">Select project manager</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('project_manager_id', $project->project_manager_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role ? $user->role->display_name : 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="client_id" class="form-label">Client (Optional)</label>
                                <select name="client_id" id="client_id" class="form-input">
                                    <option value="">Select client</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('client_id', $project->client_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role ? $user->role->display_name : 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Update Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
