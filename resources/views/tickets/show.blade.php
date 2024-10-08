<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $ticket->title }}</h1>

                <!-- Ticket Details -->
                <div class="mb-4">
                    <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
                    <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                    <p><strong>Category:</strong>
                        @foreach($ticket->categories as $category)
                            {{ $category->name }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                    <p><strong>Created At:</strong> {{ $ticket->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Description:</strong> {{ $ticket->description }}</p>
                </div>

                <!-- Activity Log -->
                <h2 class="text-xl font-semibold mt-6">Activity Log</h2>
                <ul class="list-disc pl-5 mb-4">
                    @foreach($ticket->activityLogs as $log)
                        <li>{{ $log->description }} ({{ $log->created_at->format('Y-m-d H:i:s') }})</li>
                    @endforeach
                </ul>

                <!-- Comments Section -->
                <h2 class="text-xl font-semibold mt-6">Comments</h2>
                <ul class="list-disc pl-5 mb-4">
                    @foreach($ticket->comments as $comment)
                        <li>
                            <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }} ({{ $comment->created_at->format('Y-m-d H:i:s') }})
                        </li>
                    @endforeach
                </ul>

                <!-- Add Comment Form -->
                <h3 class="text-lg font-semibold mt-6">Add a Comment</h3>
                <form method="POST" action="{{ route('comments.store', $ticket->id) }}" class="mt-2">
                    @csrf
                    <div>
                        <textarea name="content" rows="4" class="w-full border border-gray-300 rounded p-2" placeholder="Write your comment..."></textarea>
                    </div>
                    
                    <!-- Error messages -->
                    <div class="text-red-500 mt-2">
                        @error('content'){{ $message }}@enderror
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Submit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
