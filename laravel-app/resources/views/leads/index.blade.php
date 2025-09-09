<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Leads') }}
            </h2>
            @if(auth()->user()->hasRole('bd') || auth()->user()->hasRole('admin'))
                <a href="{{ route('leads.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Lead
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">All Leads</h3>
                        <div class="text-sm text-secondary">
                            Total: {{ $leads->total() }} leads
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/5">Company</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Platform</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/6">Bidding Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">Priority</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">Value</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Assigned To</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($leads as $lead)
                                <tr class="table-row hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-primary">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $lead->company_name }}</span>
                                            <span class="text-xs text-gray-500 md:hidden">{{ ucfirst($lead->bidding_platform) }}</span>
                                            <span class="text-xs text-gray-500 lg:hidden">Priority: {{ $lead->bidding_priority }}/5</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        <div class="flex items-center">
                                            @if($lead->bidding_platform === 'upwork')
                                                <i class="fab fa-upwork mr-1 text-orange-500"></i>
                                            @elseif($lead->bidding_platform === 'linkedin')
                                                <i class="fab fa-linkedin mr-1 text-blue-600"></i>
                                            @else
                                                <i class="fas fa-globe mr-1 text-gray-500"></i>
                                            @endif
                                            <span class="font-medium">{{ ucfirst($lead->bidding_platform) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="status-badge status-{{ str_replace('_', '-', $lead->bidding_status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $lead->bidding_status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $lead->bidding_priority ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                            <span class="ml-1 text-xs">{{ $lead->bidding_priority }}/5</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        @if($lead->estimated_value)
                                            <span class="font-semibold text-green-600">${{ number_format($lead->estimated_value, 2) }}</span>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        {{ $lead->assignedTo->name }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('leads.show', $lead) }}" class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-center transition-colors">View</a>
                                            @if(auth()->user()->hasRole('bd') || auth()->user()->hasRole('admin'))
                                                @if(auth()->user()->hasRole('admin') || $lead->assigned_to === auth()->id())
                                                    <a href="{{ route('leads.edit', $lead) }}" class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 text-center transition-colors">Edit</a>
                                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">No leads found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($leads->hasPages())
                        <div class="mt-6">
                            {{ $leads->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
