<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <h1>Your Tickets</h1>
                    
                    <!-- Create New Ticket Button -->
                    <div class="mb-3">
                        <a href="{{ route('tickets.create') }}" class="border border-black">Create New Ticket</a>
                    </div>
                    
                    <div class="mb-3">
                        <form method="GET" action="{{ route('tickets.index') }}">
                            <!-- Status Filter -->
                            <label for="status">Status</label>
                            <select name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>

                            <!-- Priority Filter -->
                            <label for="priority">Priority</label>
                            <select name="priority">
                                <option value="" {{ request('priority') == '' ? 'selected' : '' }}>All</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>

                            <!-- Category Filter -->
                            <label for="category_id">Category</label>
                            <select name="category_id">
                                <option value="" {{ request('category_id') == '' ? 'selected' : '' }}>All</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                
                            <button type="submit" class="border border-black">Filter</button>
                        </form>
                    </div>
                
                    <!-- Ticket table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border border-black">Title</th>
                                <th class="border border-black">Priority</th>
                                <th class="border border-black">Status</th>
                                <th class="border border-black">Category</th>
                                <th class="border border-black">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td class="border border-black"><a class="text-blue-600 underline" href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->title }}</a></td>
                                    <td class="border border-black">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="border border-black">{{ ucfirst($ticket->status) }}</td>
                                    <td class="border border-black">
                                        @foreach($ticket->categories as $category)
                                            {{ $category->name }}
                                        @endforeach
                                    </td>
                                    <td class="border border-black">{{ $ticket->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
