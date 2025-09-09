<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to Team Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Team Manager</h3>
                        <p class="text-lg text-gray-600 mb-8">
                            A comprehensive business management system for your team of 6 people.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <div class="text-3xl font-bold text-blue-600 mb-2">Projects</div>
                                <div class="text-sm text-blue-800">Manage and track all your projects</div>
                            </div>
                            
                            <div class="bg-green-50 p-6 rounded-lg">
                                <div class="text-3xl font-bold text-green-600 mb-2">Proposals</div>
                                <div class="text-sm text-green-800">Submit and track business proposals</div>
                            </div>
                            
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600 mb-2">Leads</div>
                                <div class="text-sm text-purple-800">Generate and manage business leads</div>
                            </div>
                            
                            <div class="bg-yellow-50 p-6 rounded-lg">
                                <div class="text-3xl font-bold text-yellow-600 mb-2">Team</div>
                                <div class="text-sm text-yellow-800">Collaborate with your team members</div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">
                                Please contact your administrator to assign you a role and get started.
                            </p>
                            <div class="inline-flex rounded-md shadow">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
