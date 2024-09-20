<!-- resources/views/tickets/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1>Edit Ticket</h1>

                    <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}" class="form-input" required>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" required>{{ old('description', $ticket->description) }}</textarea>
                        </div>

                        <!-- Priority Dropdown -->
                        <div class="mb-4">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <!-- Category Checkboxes -->
                        <div class="mb-4">
                            <label>Categories</label><br>
                            @foreach($categories as $category)
                                <div>
                                    <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, $ticket->categories->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label for="category_{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Labels Checkboxes -->
                        <div class="mb-4">
                            <label>Labels</label><br>
                            @foreach($labels as $label)
                                <div>
                                    <input type="checkbox" id="label_{{ $label->id }}" name="labels[]" value="{{ $label->id }}"
                                        {{ in_array($label->id, $ticket->labels->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label for="label_{{ $label->id }}">{{ $label->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Assign Agent Dropdown (Only for Admins) -->
                        @if(Auth::user()->role === 'admin') <!-- Check if user has permission to assign an agent -->
                            <div class="mb-4">
                                <label for="user_agent">Assign Agent</label>
                                <select id="user_agent" name="user_agent_id" class="form-select">
                                    <option value="">Unassigned</option> <!-- Allow ticket to remain unassigned -->
                                    @foreach($user_agents as $user_agent)
                                        <option value="{{ $user_agent->id }}" {{ $ticket->user_id == $user_agent->id ? 'selected' : '' }}>
                                            {{ $user_agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <button type="submit" class="border border-black">Update Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
