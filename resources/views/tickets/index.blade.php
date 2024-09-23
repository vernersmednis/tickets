<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Your Tickets</h1>
                
                <!-- Create New Ticket Button -->
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'regular')
                    <div class="mb-4">
                        <a href="{{ route('tickets.create') }}" class="inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition">Create New Ticket</a>
                    </div>
                @endif
                
                <div class="mb-4">
                    <form method="GET" action="{{ route('tickets.index') }}" class="flex space-x-4">
                        @csrf
                        <div>
                            <label for="status" class="block font-medium">Status</label>
                            <select name="status" class="border border-gray-300 rounded p-2">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block font-medium">Priority</label>
                            <select name="priority" class="border border-gray-300 rounded p-2">
                                <option value="" {{ request('priority') == '' ? 'selected' : '' }}>All</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block font-medium">Category</label>
                            <select name="category_id" class="border border-gray-300 rounded p-2">
                                <option value="" {{ request('category_id') == '' ? 'selected' : '' }}>All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="bg-gray-200 border border-gray-300 rounded py-2 px-4 hover:bg-gray-300 transition">Filter</button>
                    </form>
                </div>

                <!-- Responsive Ticket Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2">Title</th>
                                <th class="border border-gray-300 px-4 py-2">Priority</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Category</th>
                                <th class="border border-gray-300 px-4 py-2">Created At</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $ticket->title }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($ticket->status) }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        @foreach($ticket->categories as $category)
                                            {{ $category->name }}
                                        @endforeach
                                    </td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $ticket->created_at->format('Y-m-d') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a class="text-blue-600 underline" href="{{ route('tickets.show', $ticket->id) }}">View</a> | 
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'agent')
                                            <a class="text-blue-600 underline" href="{{ route('tickets.edit', $ticket->id) }}">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $tickets->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
