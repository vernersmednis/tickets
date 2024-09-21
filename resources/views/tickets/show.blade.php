<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1>{{ $ticket->title }}</h1>

                    <!-- Ticket Details -->
                    <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
                    <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                    <p><strong>Category:</strong>
                        @foreach($ticket->categories as $category)
                            {{ $category->name }}
                        @endforeach
                    </p>
                    <p><strong>Created At:</strong> {{ $ticket->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Description:</strong> {{ $ticket->description }}</p>

                    <!-- Activity Log -->
                    <h2>Activity Log</h2>
                    <ul>
                        @foreach($ticket->activityLogs as $log)
                            <li>{{ $log->description }} ({{ $log->created_at->format('Y-m-d H:i:s') }})</li>
                        @endforeach
                    </ul>

                    <!-- Comments Section -->
                    <h2>Comments</h2>
                    <ul>
                        @foreach($ticket->comments as $comment)
                            <li><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }} ({{ $comment->created_at->format('Y-m-d H:i:s') }})</li>
                        @endforeach
                    </ul>

                    <!-- Add Comment Form -->
                    <h3>Add a Comment</h3>
                    <form method="POST" action="{{ route('comments.store', $ticket->id) }}">
                        @csrf
                        <div>
                            <textarea name="content" rows="4" class="w-full border border-gray-300"></textarea>
                        </div>
                        
                        <!-- Error messages -->
                        <div class="text-red-500">
                            <br>
                            @error('content'){{ $message }}<br>@enderror
                        </div>
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
