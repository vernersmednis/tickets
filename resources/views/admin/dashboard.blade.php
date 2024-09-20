<!-- resources/views/admin/dashboard.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1>Dashboard</h1>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-100 border border-gray-300 rounded">
                            <h2>Total Tickets</h2>
                            <p>{{ $totalTickets }}</p>
                        </div>
                        <div class="p-4 bg-gray-100 border border-gray-300 rounded">
                            <h2>Open Tickets</h2>
                            <p>{{ $openTickets }}</p>
                        </div>
                        <div class="p-4 bg-gray-100 border border-gray-300 rounded">
                            <h2>Closed Tickets</h2>
                            <p>{{ $closedTickets }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
