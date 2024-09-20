<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use App\Models\Label;
use App\Models\ActivityLog;
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

        // Check if the user is an admin
        if ($user->role === 'admin') {
            // Admin sees all tickets
            $tickets = Ticket::query();
        } else {
            // Regular users only see their own tickets
            $tickets = Ticket::where('user_id', $user->id);
        }

        // Apply filters for status, priority, and category
        $tickets = $tickets
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
        $categories = Category::all(); // Fetch categories to pass to the view
        $users = User::all();
        $labels = Label::all();  // Fetch all labels to assign to the ticket

        // Display the form for creating a new ticket
        return view('tickets.create', compact( 'categories', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,closed',
            'category_id' => 'nullable|exists:categories,id',
            'agent_id' => 'nullable|exists:users,id', // Validate agent
            'labels' => 'nullable|array',             // Allow multiple labels
            'labels.*' => 'exists:labels,id',          // Ensure labels exist
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description, // Include description in the created ticket
            'priority' => $request->priority,
            'status' => $request->status,
            'user_id' => auth()->id(), // Assuming the user is logged in
        ]);
        
        // Sync labels and categories
        $ticket->labels()->sync($request->labels);
        $ticket->categories()->sync($request->categories);

        $activityDescription = Auth::user()->name.' created a ticket "'.$ticket->title.'" with priority "'.$ticket->priority.'" and status "'.$ticket->status.'"';
        $activityLog = ActivityLog::create([
            'ticket_id' => $ticket->id,
            'description' => $activityDescription,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // Make sure the user can only view their own tickets
        if (Auth::id() !== $ticket->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    
        // Load the ticket's comments in default order and activity logs in reverse order
        $ticket->load(['comments', 'activityLogs' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Order activity logs by created_at in descending order
        }]);
    
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        $user_agents = User::all();
        $labels = Label::all();  // Fetch all labels to assign to the ticket
        return view('tickets.edit', compact('ticket', 'categories', 'user_agents', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,closed',
            'category_id' => 'nullable|exists:categories,id',
            'agent_id' => 'nullable|exists:users,id',
            'labels' => 'nullable|array',
            'labels.*' => 'exists:labels,id',
        ]);

        // Get the original attributes
        $originalAttributes = $ticket->getOriginal();

        // Update ticket details
        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'user_id' => $request->user_agent_id,
        ]);

        // Sync labels and categories
        $labelSync = $ticket->labels()->sync($request->labels);
        $categorySync = $ticket->categories()->sync($request->categories);

        // Build activity description based on changed attributes
        $changedAttributes = [];
        foreach (['title', 'description', 'priority', 'status'] as $attribute) {
            if ($originalAttributes[$attribute] !== $ticket->$attribute) {
                $changedAttributes[] = ucfirst($attribute) . ' changed from "' . $originalAttributes[$attribute] . '" to "' . $ticket->$attribute . '"';
            }
        }

        // Fetch added and removed label names
        if (!empty($labelSync['attached'])) {
            $addedLabels = \App\Models\Label::whereIn('id', $labelSync['attached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Labels added: "' . implode(', ', $addedLabels) . '"';
        }
        if (!empty($labelSync['detached'])) {
            $removedLabels = \App\Models\Label::whereIn('id', $labelSync['detached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Labels removed: "' . implode(', ', $removedLabels) . '"';
        }

        // Fetch added and removed category names
        if (!empty($categorySync['attached'])) {
            $addedCategories = \App\Models\Category::whereIn('id', $categorySync['attached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Categories added: "' . implode(', ', $addedCategories) . '"';
        }
        if (!empty($categorySync['detached'])) {
            $removedCategories = \App\Models\Category::whereIn('id', $categorySync['detached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Categories removed: "' . implode(', ', $removedCategories) . '"';
        }

        // Construct the final activity description
        $activityDescription = Auth::user()->name . ' edited a ticket "' . $ticket->title . '" with changes: ' . implode(', ', $changedAttributes);

        // Log the activity
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'description' => $activityDescription,
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
