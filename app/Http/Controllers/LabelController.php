<?php
namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;

class LabelController extends Controller
{
    // Display a list of all labels
    public function index()
    {
        // Fetch all labels using the model method
        $labels = Label::getAllLabels();
        
        // Pass the retrieved labels to the view for rendering
        return view('admin.labels.index', compact('labels'));
    }

    // Store a new label in the database
    public function store(StoreLabelRequest $request)
    {
        // Retrieve and validate data from the incoming request
        $validatedData = $request->validated();

        // Create a new label using the model method
        Label::createLabel($validatedData);

        // Redirect to the labels index with a success message
        return redirect()->route('admin.labels.index')->with('success', 'Label created successfully.');
    }

    // Update an existing label in the database
    public function update(UpdateLabelRequest $request, Label $label)
    {
        // Retrieve and validate data from the incoming request
        $validatedData = $request->validated();

        // Update the existing label using the model method
        $label->updateLabel($validatedData);

        // Redirect to the labels index with a success message
        return redirect()->route('admin.labels.index')->with('success', 'Label updated successfully.');
    }

    // Delete a specified label from the database
    public function destroy(Label $label)
    {
        // Remove the label from the database
        $label->delete();

        // Redirect to the labels index with a success message
        return redirect()->route('admin.labels.index')->with('success', 'Label deleted successfully.');
    }
}
