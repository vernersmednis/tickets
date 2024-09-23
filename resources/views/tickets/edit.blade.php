<!-- resources/views/tickets/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Edit Ticket</h1>

                <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}" class="form-input border border-gray-300 rounded p-2 w-full" required>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium">Description</label>
                        <textarea id="description" name="description" class="form-textarea border border-gray-300 rounded p-2 w-full" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <!-- Priority Dropdown -->
                    <div class="mb-4">
                        <label for="priority" class="block font-medium">Priority</label>
                        <select id="priority" name="priority" class="form-select border border-gray-300 rounded p-2 w-full" required>
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="mb-4">
                        <label for="status" class="block font-medium">Status</label>
                        <select id="status" name="status" class="form-select border border-gray-300 rounded p-2 w-full" required>
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <!-- Category Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-medium">Categories</label>
                        @foreach($categories as $category)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, $ticket->categories->pluck('id')->toArray()) ? 'checked' : '' }} class="mr-2">
                                <label for="category_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Labels Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-medium">Labels</label>
                        @foreach($labels as $label)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="label_{{ $label->id }}" name="labels[]" value="{{ $label->id }}"
                                    {{ in_array($label->id, $ticket->labels->pluck('id')->toArray()) ? 'checked' : '' }} class="mr-2">
                                <label for="label_{{ $label->id }}">{{ $label->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Assign Agent Dropdown (Only for Admins) -->
                    @if(Auth::user()->role === 'admin')
                        <div class="mb-4">
                            <label for="user_agent" class="block font-medium">Assign Agent</label>
                            <select id="user_agent" name="user_agent_id" class="form-select border border-gray-300 rounded p-2 w-full">
                                @foreach($user_agents as $user_agent)
                                    <option value="{{ $user_agent->id }}" {{ $ticket->user_id == $user_agent->id ? 'selected' : '' }}>
                                        {{ $user_agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Update Ticket</button>

                    <!-- Error messages -->
                    <div class="text-red-500 mt-4">
                        @error('title'){{ $message }}<br>@enderror
                        @error('description'){{ $message }}<br>@enderror
                        @error('priority'){{ $message }}<br>@enderror
                        @error('status'){{ $message }}<br>@enderror
                        @error('categories.*'){{ $message }}<br>@enderror
                        @error('labels.*'){{ $message }}<br>@enderror
                        @if(Auth::user()->role === 'admin') @error('user_agent_id'){{ $message }}<br>@enderror @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
