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
                        <div class="mb-4">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-input" required>
                        </div>
                        <div class="mb-4">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" rows="4"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" class="form-select" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="open">Open</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="border border-black">Create Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
