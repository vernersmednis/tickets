<!-- resources/views/tickets/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Create a New Ticket</h1>

                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf
                    
                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium">Title</label>
                        <input type="text" id="title" name="title" class="form-input border border-gray-300 rounded p-2 w-full" required>
                    </div>
                    
                    <!-- Description Field -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium">Description</label>
                        <textarea id="description" name="description" class="form-textarea border border-gray-300 rounded p-2 w-full" rows="4"></textarea>
                    </div>

                    <!-- Priority Dropdown -->
                    <div class="mb-4">
                        <label for="priority" class="block font-medium">Priority</label>
                        <select id="priority" name="priority" class="form-select border border-gray-300 rounded p-2 w-full" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="mb-4">
                        <label for="status" class="block font-medium">Status</label>
                        <select id="status" name="status" class="form-select border border-gray-300 rounded p-2 w-full" required>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <!-- Category Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-medium">Categories</label>
                        @foreach($categories as $category)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" class="mr-2">
                                <label for="category_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Labels Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-medium">Labels</label>
                        @foreach($labels as $label)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="label_{{ $label->id }}" name="labels[]" value="{{ $label->id }}" class="mr-2">
                                <label for="label_{{ $label->id }}">{{ $label->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Create Ticket</button>
                
                    <!-- Error messages -->
                    <div class="text-red-500 mt-4">
                        @error('title'){{ $message }}<br>@enderror
                        @error('description'){{ $message }}<br>@enderror
                        @error('priority'){{ $message }}<br>@enderror
                        @error('status'){{ $message }}<br>@enderror
                        @error('categories.*'){{ $message }}<br>@enderror
                        @error('labels.*'){{ $message }}<br>@enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
