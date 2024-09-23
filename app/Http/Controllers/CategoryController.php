<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    // Display a list of all categories
    public function index()
    {
        // Fetch all categories using the model method
        $categories = Category::getAllCategories();
        
        // Pass the retrieved categories to the view for rendering
        return view('admin.categories.index', compact('categories'));
    }

    // Store a new category in the database
    public function store(StoreCategoryRequest $request)
    {
        // Retrieve and validate data from the incoming request
        $validatedData = $request->validated();

        // Create a new category using the model method
        Category::createCategory($validatedData);

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    // Update an existing category in the database
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Retrieve and validate data from the incoming request
        $validatedData = $request->validated();
        
        // Update the existing category using the model method
        $category->updateCategory($validatedData);

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    // Delete a specified category from the database
    public function destroy(Category $category)
    {
        // Remove the category from the database
        $category->delete();

        // Redirect to the categories index with a success message
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
