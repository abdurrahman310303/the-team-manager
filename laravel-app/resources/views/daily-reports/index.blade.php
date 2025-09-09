<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Reports') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">My Daily Reports</h3>
                        <a href="{{ route('daily-reports.create') }}" class="btn-primary">
                            Submit Daily Report
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Work Summary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reports as $report)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                        {{ $report->report_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                        {{ $report->project->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge 
                                            @if($report->report_type == 'developer') status-developer
                                            @elseif($report->report_type == 'bd') status-bd
                                            @else status-pending
                                            @endif">
                                            {{ ucfirst($report->report_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                        {{ $report->hours_worked }}h
                                    </td>
                                    <td class="px-6 py-4 text-sm text-secondary">
                                        {{ Str::limit($report->work_completed, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-wrap gap-1">
                                            <a href="{{ route('daily-reports.show', $report) }}" class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200">View</a>
                                            <a href="{{ route('daily-reports.edit', $report) }}" class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200">Edit</a>
                                            <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-block px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-secondary">No daily reports found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
