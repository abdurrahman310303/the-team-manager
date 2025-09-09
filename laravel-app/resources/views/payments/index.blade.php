<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Payments') }}
            </h2>
            <a href="{{ route('payments.create') }}" class="btn-primary">
                Record New Payment
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">
                            @if(auth()->user()->hasRole('investor'))
                                All Payments
                            @else
                                My Payments
                            @endif
                        </h3>
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
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/5">Purpose</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Project</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/6">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-1/6">Currency</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-16">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Date</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($payments as $payment)
                                <tr class="table-row hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-primary">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $payment->fund_purpose)) }}</span>
                                            <span class="text-xs text-gray-500 md:hidden">{{ $payment->project ? $payment->project->name : 'General' }}</span>
                                            <span class="text-xs text-gray-500 lg:hidden">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        {{ $payment->project ? $payment->project->name : 'General' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-semibold text-green-600">
                                        <div class="flex flex-col">
                                            <span>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                                            @if($payment->amount_usd && $payment->currency !== 'USD')
                                                <span class="text-xs text-gray-500">≈ ${{ number_format($payment->amount_usd, 2) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden lg:table-cell">
                                        <span class="font-medium">{{ $payment->currency }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="status-badge status-{{ $payment->status }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        <span class="text-xs">{{ $payment->payment_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('payments.show', $payment) }}" class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-center transition-colors">View</a>
                                            <a href="{{ route('payments.edit', $payment) }}" class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 text-center transition-colors">Edit</a>
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-secondary">No payments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
