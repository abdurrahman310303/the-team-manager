<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: var(--light-gray); min-height: 100vh;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-primary">All Users</h3>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.overview') }}" class="btn-secondary">
                                Back to Overview
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                                Add New User
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-container">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/4">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/4">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider w-1/6">Role</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-16">Projects</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-16">Proposals</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider hidden lg:table-cell w-16">Leads</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell w-1/6">Created</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider w-24">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr class="table-row hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm font-medium text-primary">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-500 md:hidden truncate">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        <span class="truncate block">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-4 py-4">
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
                                    <td class="px-4 py-4 text-center text-sm text-secondary hidden lg:table-cell">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                            {{ $user->managedProjects->count() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-secondary hidden lg:table-cell">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                            {{ $user->proposals->count() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm text-secondary hidden lg:table-cell">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                            {{ $user->leads->count() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-secondary hidden md:table-cell">
                                        <span class="text-xs">{{ $user->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col gap-1">
                                            <a href="{{ route('admin.users.show', $user) }}" class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 text-center transition-colors">View</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 text-center transition-colors">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
