<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Admin Overview - All Team Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    Manage Users
                </a>
                <a href="{{ route('projects.create') }}" class="btn-primary">
                    New Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="dashboard-stat">
                    <div class="dashboard-stat-value">{{ $stats['total_users'] }}</div>
                    <div class="dashboard-stat-label">Total Users</div>
                </div>
                <div class="dashboard-stat">
                    <div class="dashboard-stat-value">{{ $stats['total_projects'] }}</div>
                    <div class="dashboard-stat-label">Total Projects</div>
                </div>
                <div class="dashboard-stat">
                    <div class="dashboard-stat-value">{{ $stats['total_proposals'] }}</div>
                    <div class="dashboard-stat-label">Total Proposals</div>
                </div>
                <div class="dashboard-stat">
                    <div class="dashboard-stat-value">{{ $stats['total_leads'] }}</div>
                    <div class="dashboard-stat-label">Total Leads</div>
                </div>
            </div>

            <!-- Users by Role -->
            <div class="dashboard-card mb-8">
                <h3 class="text-lg font-semibold text-primary mb-4">Users by Role</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($stats['users_by_role'] as $role => $count)
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-2xl font-bold text-primary">{{ $count }}</div>
                        <div class="text-sm text-secondary">{{ ucfirst(str_replace('_', ' ', $role)) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Projects by Status -->
            <div class="dashboard-card mb-8">
                <h3 class="text-lg font-semibold text-primary mb-4">Projects by Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($stats['projects_by_status'] as $status)
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="text-2xl font-bold text-primary">{{ $status->count }}</div>
                        <div class="text-sm text-secondary">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Team Members Table -->
            <div class="dashboard-card mb-8">
                <h3 class="text-lg font-semibold text-primary mb-6">Team Members</h3>
                <div class="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="table-header">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Projects</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Proposals</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Leads</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ substr($user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-primary">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role)
                                        <span class="status-badge status-{{ $user->role->name }}">
                                            {{ $user->role->display_name }}
                                        </span>
                                    @else
                                        <span class="status-badge" style="background-color: #f3f4f6; color: #6b7280;">
                                            No Role
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                    {{ $user->managedProjects->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                    {{ $user->proposals->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                    {{ $user->leads->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Projects -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Recent Projects</h3>
                    <div class="space-y-4">
                        @forelse($recent_activities->where('type', 'project')->take(5) as $activity)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-primary">{{ $activity['title'] }}</div>
                                <div class="text-xs text-secondary">{{ $activity['description'] }}</div>
                            </div>
                            <div class="text-xs text-muted">{{ $activity['time'] }}</div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-4">No recent projects</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Proposals -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Recent Proposals</h3>
                    <div class="space-y-4">
                        @forelse($recent_activities->where('type', 'proposal')->take(5) as $activity)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-primary">{{ $activity['title'] }}</div>
                                <div class="text-xs text-secondary">{{ $activity['description'] }}</div>
                            </div>
                            <div class="text-xs text-muted">{{ $activity['time'] }}</div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-4">No recent proposals</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>