<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Lead Details') }}
            </h2>
            <div class="flex gap-2">
                @if(auth()->user()->hasRole('bd') || auth()->user()->hasRole('admin'))
                    @if(auth()->user()->hasRole('admin') || $lead->assigned_to === auth()->id())
                        <a href="{{ route('leads.edit', $lead) }}" class="btn-secondary">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Lead
                        </a>
                    @endif
                @endif
                <a href="{{ route('leads.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Leads
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Lead Information -->
                <div class="lg:col-span-2">
                    <div class="dashboard-card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary mb-4">Lead Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Company Name</label>
                                    <p class="text-lg font-semibold text-primary">{{ $lead->company_name }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Contact Person</label>
                                    <p class="text-lg font-semibold text-primary">{{ $lead->contact_person }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email</label>
                                    <p class="text-primary">{{ $lead->email }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Phone</label>
                                    <p class="text-primary">{{ $lead->phone ?: 'N/A' }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Status</label>
                                    <p>
                                        <span class="status-badge status-{{ str_replace('_', '-', $lead->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Source</label>
                                    <p class="text-primary">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Estimated Value</label>
                                    <p class="text-lg font-semibold text-green-600">
                                        @if($lead->estimated_value)
                                            ${{ number_format($lead->estimated_value, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Last Contact Date</label>
                                    <p class="text-primary">
                                        {{ $lead->last_contact_date ? $lead->last_contact_date->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            @if($lead->description)
                                <div class="mt-6">
                                    <label class="text-sm font-medium text-gray-500">Description</label>
                                    <p class="text-primary mt-1">{{ $lead->description }}</p>
                                </div>
                            @endif

                            @if($lead->notes)
                                <div class="mt-6">
                                    <label class="text-sm font-medium text-gray-500">Notes</label>
                                    <p class="text-primary mt-1">{{ $lead->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Assignment Information -->
                    <div class="dashboard-card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary mb-4">Assignment</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Assigned To</label>
                                    <p class="text-primary font-semibold">{{ $lead->assignedTo->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $lead->assignedTo->role->display_name ?? 'No Role' }}</p>
                                </div>

                                @if($lead->project)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Related Project</label>
                                        <p class="text-primary font-semibold">{{ $lead->project->name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $lead->project->status)) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lead Statistics -->
                    <div class="dashboard-card">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary mb-4">Lead Statistics</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Created</span>
                                    <span class="text-sm font-semibold text-primary">{{ $lead->created_at->format('M d, Y') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Last Updated</span>
                                    <span class="text-sm font-semibold text-primary">{{ $lead->updated_at->format('M d, Y') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Days Since Created</span>
                                    <span class="text-sm font-semibold text-primary">{{ $lead->created_at->diffInDays(now()) }} days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
