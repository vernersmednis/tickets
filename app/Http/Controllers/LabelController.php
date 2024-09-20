<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    // Show all labels
    public function index()
    {
        $labels = Label::all(); // Fetch all labels from the database
        return view('labels.index', compact('labels')); // Pass labels to the view
    }

    // Store a new label
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name', // Validate input
        ]);

        Label::create([
            'name' => $request->name,
        ]);

        return redirect()->route('labels.index')->with('success', 'Label created successfully.');
    }

    // Update an existing label
    public function update(Request $request, Label $label)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name,' . $label->id, // Validate input
        ]);

        $label->update([
            'name' => $request->name,
        ]);

        return redirect()->route('labels.index')->with('success', 'Label updated successfully.');
    }

    // Delete a label
    public function destroy(Label $label)
    {
        $label->delete();

        return redirect()->route('labels.index')->with('success', 'Label deleted successfully.');
    }
}
