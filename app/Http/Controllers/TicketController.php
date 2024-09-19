<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string|in:open,closed', // Adjust based on your status options
            'priority' => 'nullable|string|in:low,medium,high', // Adjust based on your priority options
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect or return a response with validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Apply filters for status, priority, and category
        $tickets = Ticket::where('user_id', $user->id)
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->priority, function($query) use ($request) {
                $query->where('priority', $request->priority);
            })
            ->when($request->category_id, function($query) use ($request) {
                $query->whereHas('categories', function($query) use ($request) {
                    $query->where('categories.id', $request->category_id);
                });
            })
            ->get();

        $categories = Category::all();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch categories to pass to the view
        $categories = Category::all();

        // Display the form for creating a new ticket
        return view('tickets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate and store the new ticket
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string', // Ensure description is validated
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,closed',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description, // Include description in the created ticket
            'priority' => $request->priority,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(), // Assuming the user is logged in
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // Make sure the user can only view their own tickets
        if (Auth::id() !== $ticket->user_id) {
            abort(403, 'Unauthorized');
        }
    
        // Load the ticket's comments and activity logs (assuming these relations exist)
        $ticket->load('comments', 'activityLogs');
    
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
