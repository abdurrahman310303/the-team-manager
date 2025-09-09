<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Profit Share Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('profit-shares.index') }}" class="btn-secondary">
                    Back to Profit Shares
                </a>
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('profit-shares.edit', $profitShare) }}" class="btn-primary">
                    Edit Profit Share
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-primary">Profit Share Details</h3>
                            <p class="text-secondary mt-2">Project: {{ $profitShare->project->name }}</p>
                        </div>
                        <span class="status-badge status-{{ $profitShare->status }}">
                            {{ ucfirst($profitShare->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">${{ number_format($profitShare->amount, 2) }}</div>
                            <div class="text-sm text-secondary">Amount</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">{{ number_format($profitShare->percentage, 2) }}%</div>
                            <div class="text-sm text-secondary">Percentage</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">{{ $profitShare->user->name }}</div>
                            <div class="text-sm text-secondary">Recipient</div>
                        </div>
                        <div class="text-center p-4 border border-gray-200 rounded-lg">
                            <div class="text-2xl font-bold text-primary">
                                {{ $profitShare->calculated_date->format('M d, Y') }}
                            </div>
                            <div class="text-sm text-secondary">Calculated Date</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Project Information -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Project Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Project Name:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->project->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Project Budget:</span>
                            <span class="text-sm font-medium text-primary">${{ number_format($profitShare->project->budget, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Project Status:</span>
                            <span class="status-badge status-{{ str_replace('_', '-', $profitShare->project->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $profitShare->project->status)) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Project Manager:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->project->projectManager->name }}</span>
                        </div>
                        @if($profitShare->project->start_date)
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Start Date:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->project->start_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                        @if($profitShare->project->end_date)
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">End Date:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->project->end_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="dashboard-card">
                    <h3 class="text-lg font-semibold text-primary mb-4">Recipient Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Name:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Email:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Role:</span>
                            <span class="status-badge status-{{ $profitShare->user->role ? $profitShare->user->role->name : 'no-role' }}">
                                {{ $profitShare->user->role ? $profitShare->user->role->display_name : 'No Role' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="dashboard-card mt-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Status:</span>
                            <span class="status-badge status-{{ $profitShare->status }}">
                                {{ ucfirst($profitShare->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Calculated Date:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->calculated_date->format('M d, Y') }}</span>
                        </div>
                        @if($profitShare->paid_date)
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Paid Date:</span>
                            <span class="text-sm font-medium text-primary">{{ $profitShare->paid_date->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Percentage:</span>
                            <span class="text-sm font-medium text-primary">{{ number_format($profitShare->percentage, 2) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Amount:</span>
                            <span class="text-sm font-medium text-primary">${{ number_format($profitShare->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($profitShare->notes)
            <div class="dashboard-card mt-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Notes</h3>
                <p class="text-secondary">{{ $profitShare->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
