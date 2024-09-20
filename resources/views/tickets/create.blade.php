<!-- resources/views/tickets/create.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <h1>Create a New Ticket</h1>

                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf
                        
                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-input" required>
                        </div>
                        
                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" rows="4"></textarea>
                        </div>

                        <!-- Priority Dropdown -->
                        <div class="mb-4">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <!-- Category Checkboxes -->
                        <div class="mb-4">
                            <label>Categories</label><br>
                            @foreach($categories as $category)
                                <div>
                                    <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
                                    <label for="category_{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Labels Checkboxes -->
                        <div class="mb-4">
                            <label>Labels</label><br>
                            @foreach($labels as $label)
                                <div>
                                    <input type="checkbox" id="label_{{ $label->id }}" name="labels[]" value="{{ $label->id }}">
                                    <label for="label_{{ $label->id }}">{{ $label->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="border border-black">Create Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
