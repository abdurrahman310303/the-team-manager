<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-primary leading-tight">
                {{ __('Profit Shares') }}
            </h2>
            @if(auth()->user()->hasRole('admin'))
                <div class="flex space-x-2">
                    <a href="{{ route('profit-shares.create') }}" class="btn-primary">
                        Create Profit Share
                    </a>
                    <button onclick="openCalculator()" class="btn-secondary">
                        Calculate Shares
                    </button>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profit Calculator Modal (Admin Only) -->
            @if(auth()->user()->hasRole('admin'))
            <div id="calculatorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="dashboard-card max-w-md w-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary mb-4">Calculate Profit Shares</h3>
                            <form action="{{ route('profit-shares.calculate') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="project_id" class="form-label">Select Project</label>
                                    <select name="project_id" id="project_id" required class="form-input">
                                        <option value="">Choose a completed project</option>
                                        @foreach(\App\Models\Project::where('status', 'completed')->get() as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="total_profit" class="form-label">Total Profit ($)</label>
                                    <input type="number" name="total_profit" id="total_profit" required 
                                        min="0" step="0.01" class="form-input" placeholder="Enter total profit">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeCalculator()" class="btn-secondary">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        Calculate & Create
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">
                            @if(auth()->user()->hasRole('admin'))
                                All Profit Shares
                            @else
                                My Profit Shares
                            @endif
                        </h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Calculated Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Paid Date</th>
                                    @if(auth()->user()->hasRole('admin'))
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($profitShares as $profitShare)
                                <tr class="table-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-primary">{{ $profitShare->project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-secondary">{{ $profitShare->user->name }}</div>
                                        <div class="text-xs text-muted">{{ $profitShare->user->role ? $profitShare->user->role->display_name : 'No Role' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                        {{ number_format($profitShare->percentage, 2) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
                                        ${{ number_format($profitShare->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-{{ $profitShare->status }}">
                                            {{ ucfirst($profitShare->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                        {{ $profitShare->calculated_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary">
                                        {{ $profitShare->paid_date ? $profitShare->paid_date->format('M d, Y') : 'Not Paid' }}
                                    </td>
                                    @if(auth()->user()->hasRole('admin'))
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('profit-shares.show', $profitShare) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('profit-shares.edit', $profitShare) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                            <form action="{{ route('profit-shares.destroy', $profitShare) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->hasRole('admin') ? '8' : '7' }}" class="px-6 py-4 text-center text-sm text-secondary">No profit shares found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $profitShares->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCalculator() {
            document.getElementById('calculatorModal').classList.remove('hidden');
        }

        function closeCalculator() {
            document.getElementById('calculatorModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('calculatorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCalculator();
            }
        });
    </script>
</x-app-layout>
