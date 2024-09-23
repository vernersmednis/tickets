<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Labels Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Create New Label Form -->
                    <h2 class="text-lg font-semibold mb-4">Create New Label</h2>
                    <form method="POST" action="{{ route('admin.labels.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Label Name</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Label</button>
                    </form>
                    <!-- Error messages -->
                    <div class="text-lg text-red-500">
                        <br>
                        @error('name'){{ $message }}<br>@enderror
                    </div>

                    <!-- Labels Table -->
                    <h2 class="text-lg font-semibold mt-8 mb-4">Existing Labels</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($labels as $label)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Edit Button -->
                                        <a href="#" class="text-blue-600 hover:text-blue-900" onclick="document.getElementById('edit-form-{{ $label->id }}').classList.toggle('hidden')">Edit</a>
                                        
                                        <!-- Edit Form -->
                                        <form id="edit-form-{{ $label->id }}" action="{{ route('admin.labels.update', $label->id) }}" method="POST" class="hidden mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-4">
                                                <label for="edit-name-{{ $label->id }}" class="block text-sm font-medium text-gray-700">New Name</label>
                                                <input type="text" id="edit-name-{{ $label->id }}" name="name" value="{{ $label->name }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            </div>
                                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
                                        </form>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.labels.destroy', $label->id) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this label?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
