<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Projects') }}
            </h2>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    Create New Project
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">All Projects</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/4">Name</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Budget</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">Manager</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">Start Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">End Date</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($projects as $project)
                                <tr class="table-row hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-primary">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $project->name }}</span>
                                            <span class="text-xs text-gray-500 md:hidden">${{ number_format($project->budget, 2) }}</span>
                                            <span class="text-xs text-gray-500 lg:hidden">{{ $project->projectManager->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        <span class="font-semibold text-green-600">${{ number_format($project->budget, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        {{ $project->projectManager->name }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        <span class="text-xs">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        <span class="text-xs">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('projects.show', $project) }}" class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-center transition-colors">View</a>
                                            @if(auth()->user()->hasRole('admin'))
                                                <a href="{{ route('projects.edit', $project) }}" class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 text-center transition-colors">Edit</a>
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">No projects found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
