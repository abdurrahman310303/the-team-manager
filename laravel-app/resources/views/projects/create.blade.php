<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Create New Project') }}
            </h2>
            <a href="{{ route('projects.index') }}" class="btn-secondary">
                Back to Projects
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">Project Details</h3>
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

                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="form-label">Project Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="form-input" placeholder="Enter project name">
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-input" placeholder="Enter project description">{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" required class="form-input">
                                    <option value="">Select status</option>
                                    <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="budget" class="form-label">Budget ($)</label>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" min="0" step="0.01"
                                    class="form-input" placeholder="Enter project budget">
                            </div>

                            <div>
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                    class="form-input">
                            </div>

                            <div>
                                <label for="project_manager_id" class="form-label">Project Manager *</label>
                                <select name="project_manager_id" id="project_manager_id" required class="form-input">
                                    <option value="">Select project manager</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('project_manager_id') == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('client_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->role ? $user->role->display_name : 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('projects.index') }}" class="btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                Create Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
