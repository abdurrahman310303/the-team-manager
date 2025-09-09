<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Project Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.index') }}" class="btn-secondary">
                    Back to Projects
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="btn-primary">
                    Edit Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-primary">{{ $project->name }}</h3>
                            <p class="text-secondary mt-2">{{ $project->description }}</p>
                        </div>
                        <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">${{ number_format($project->budget, 2) }}</div>
                            <div class="text-sm text-secondary">Budget</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">{{ $project->projectManager->name }}</div>
                            <div class="text-sm text-secondary">Project Manager</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">
                                {{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="text-sm text-secondary">Start Date</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">
                                {{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="text-sm text-secondary">End Date</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($project->client)
            <div class="dashboard-card mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">Client Information</h3>
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($project->client->name, 0, 2) }}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-primary">{{ $project->client->name }}</div>
                            <div class="text-sm text-secondary">{{ $project->client->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Project Proposals -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Proposals ({{ $project->proposals->count() }})</h3>
                    <div class="space-y-3">
                        @forelse($project->proposals->take(5) as $proposal)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-primary">{{ $proposal->title }}</div>
                                <div class="text-xs text-secondary">${{ number_format($proposal->proposed_budget, 2) }}</div>
                            </div>
                            <span class="status-badge status-{{ str_replace('_', '-', $proposal->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-4">No proposals found</div>
                        @endforelse
                    </div>
                </div>

                <!-- Project Leads -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Leads ({{ $project->leads->count() }})</h3>
                    <div class="space-y-3">
                        @forelse($project->leads->take(5) as $lead)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-primary">{{ $lead->company_name }}</div>
                                <div class="text-xs text-secondary">{{ $lead->contact_person }}</div>
                            </div>
                            <span class="status-badge status-{{ str_replace('_', '-', $lead->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-4">No leads found</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
