<x-app-layout>
    <x-slot name="header">
        <!-- Header for the page, displaying the title "Categories Management" -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Section for creating a new category -->
                    <h2 class="text-lg font-semibold mb-4">Create New Category</h2>
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="mb-4">
                            <!-- Input field for category name -->
                            <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <!-- Submit button for creating a category -->
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Category</button>
                    </form>
                    
                    <!-- Display error messages for the category name input -->
                    <div class="text-lg text-red-500">
                        <br>
                        @error('name'){{ $message }}<br>@enderror
                    </div>

                    <!-- Section for displaying existing categories -->
                    <h2 class="text-lg font-semibold mt-8 mb-4">Existing Categories</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- Table headers for categories name and actions -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Loop through each category and display in the table -->
                            @foreach ($categories as $category)
                                <tr>
                                    <!-- Display the category name -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Edit Button: Toggles the edit form visibility -->
                                        <a href="#" class="text-blue-600 hover:text-blue-900" onclick="document.getElementById('edit-form-{{ $category->id }}').classList.toggle('hidden')">Edit</a>
                                        
                                        <!-- Edit Form for updating category name -->
                                        <form id="edit-form-{{ $category->id }}" action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="hidden mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-4">
                                                <!-- Input field for new category name -->
                                                <label for="edit-name-{{ $category->id }}" class="block text-sm font-medium text-gray-700">New Name</label>
                                                <input type="text" id="edit-name-{{ $category->id }}" name="name" value="{{ $category->name }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                            </div>
                                            <!-- Submit button for updating the category -->
                                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Update</button>
                                        </form>
                                        
                                        <!-- Delete Button for removing the category -->
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this categories?')">Delete</button>
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
