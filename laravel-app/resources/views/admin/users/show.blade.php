<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details: ' . $user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">User Information</h3>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Users
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit User
                            </a>
                        </div>
                    </div>

                    <!-- User Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Basic Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Name:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Email:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Role:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($user->role && $user->role->name == 'admin') bg-red-100 text-red-800
                                        @elseif($user->role && $user->role->name == 'developer') bg-blue-100 text-blue-800
                                        @elseif($user->role && $user->role->name == 'investor') bg-green-100 text-green-800
                                        @elseif($user->role && $user->role->name == 'bd') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif ml-2">
                                        {{ $user->role ? $user->role->display_name : 'No Role' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Member Since:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Activity Summary</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Projects Managed:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->managedProjects->count() }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Proposals Submitted:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->proposals->count() }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Leads Assigned:</span>
                                    <span class="text-sm text-gray-900 ml-2">{{ $user->leads->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User's Projects -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Managed Projects</h4>
                        @if($user->managedProjects->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->managedProjects as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $project->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($project->status == 'in_progress') bg-green-100 text-green-800
                                                    @elseif($project->status == 'completed') bg-blue-100 text-blue-800
                                                    @elseif($project->status == 'on_hold') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($project->budget, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $project->client->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No projects managed by this user.</p>
                        @endif
                    </div>

                    <!-- User's Proposals -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Submitted Proposals</h4>
                        @if($user->proposals->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->proposals as $proposal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('proposals.show', $proposal) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $proposal->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($proposal->status == 'accepted') bg-green-100 text-green-800
                                                    @elseif($proposal->status == 'rejected') bg-red-100 text-red-800
                                                    @elseif($proposal->status == 'under_review') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($proposal->proposed_budget, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $proposal->project->name }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No proposals submitted by this user.</p>
                        @endif
                    </div>

                    <!-- User's Leads -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Assigned Leads</h4>
                        @if($user->leads->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($user->leads as $lead)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('leads.show', $lead) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $lead->company_name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $lead->contact_person }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($lead->status == 'new') bg-blue-100 text-blue-800
                                                    @elseif($lead->status == 'qualified') bg-green-100 text-green-800
                                                    @elseif($lead->status == 'closed_won') bg-purple-100 text-purple-800
                                                    @elseif($lead->status == 'closed_lost') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($lead->estimated_value, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No leads assigned to this user.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
