<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use App\Models\Label;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\IndexTicketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Mail\TicketCreated;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource (tickets).
     * Regular and agent users can see only their tickets, while admins can see all tickets.
     * Filters such as status, priority, and category can be applied.
     */
    public function index(Request $request) // Use IndexTicketRequest to handle incoming request filters
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Retrieve tickets filtered by user role (admin sees all, others see their own)
        // Filters include status, priority, and category
        $tickets = Ticket::filter($user, $request->only(['status', 'priority', 'category_id']))
            ->paginate(10); // Adjust the number as needed;
        // Fetch all categories to pass to the view
        $categories = Category::allCategories();

        // Return the 'tickets.index' view with the tickets and categories
        return view('tickets.index', compact('tickets', 'categories'));
    }

    /**
     * Show the form for creating a new ticket.
     * This fetches necessary data such as categories, users, and labels.
     */
    public function create()
    {
        // Fetch all available categories
        $categories = Category::allCategories();
        
        // Fetch all users (could be for assigning the ticket to a specific user)
        $users = User::allUsers();
        
        // Fetch all labels (to tag tickets with specific labels)
        $labels = Label::allLabels();

        // Return the 'tickets.create' view with categories, users, and labels
        return view('tickets.create', compact('categories', 'users', 'labels'));
    }

    /**
     * Store a newly created ticket in the database.
     * Validates the input, creates the ticket, and logs the creation activity.
     */
    public function store(StoreTicketRequest $request)
    {
        // Validate the request data
        $validatedData = $request->validated();

        // Create a new ticket and sync labels and categories with the given validated data
        $ticket = Ticket::createTicket($validatedData, auth()->id());

        // Log the activity when a new ticket is created
        ActivityLog::logTicketCreation($ticket);

        // Send email to admin
        Mail::to('admin@admin.com')->send(new TicketCreated($ticket));

        // Redirect back to the tickets index with a success message
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the details of a specific ticket.
     * Ensures the user is authorized to view the ticket.
     */
    public function show(Ticket $ticket)
    {
        // Ensure the user can only view their own tickets, unless they are an admin
        if (Auth::id() !== $ticket->user_agent_id && Auth::user()->role !== 'admin') {
            // If unauthorized, throw a 403 Forbidden error
            abort(403, 'Unauthorized');
        }

        // Load related data such as comments and activity logs for the ticket
        $ticket->loadWithRelations();

        // Return the 'tickets.show' view with the ticket details
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing a specific ticket.
     * Ensures the user is authorized to edit the ticket.
     */
    public function edit(Ticket $ticket)
    {
        // Ensure the user can only edit their own tickets, unless they are an admin
        if (Auth::id() !== $ticket->user_agent_id && Auth::user()->role !== 'admin') {
            // If unauthorized, throw a 403 Forbidden error
            abort(403, 'Unauthorized');
        }

        // Fetch all available categories for selection in the form
        $categories = Category::allCategories();
        
        // Fetch all users (e.g., agents) for potential assignment to the ticket
        $user_agents = User::allUsers();
        
        // Fetch all labels to allow tagging of the ticket
        $labels = Label::allLabels();

        // Return the 'tickets.edit' view with the ticket, categories, users, and labels
        return view('tickets.edit', compact('ticket', 'categories', 'user_agents', 'labels'));
    }

    /**
     * Update an existing ticket in the database.
     * Ensures the user is authorized to update the ticket.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // Ensure the user can only update their own tickets, unless they are an admin
        if (Auth::id() !== $ticket->user_agent_id && Auth::user()->role !== 'admin') {
            // If unauthorized, throw a 403 Forbidden error
            abort(403, 'Unauthorized');
        }

        // Validate the request data
        $validatedData = $request->validated();

        // Update the ticket with the validated data
        $ticket->updateTicket($validatedData);

        // Redirect to the ticket details page with a success message
        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove a ticket from the database (soft delete or permanent delete, depending on implementation).
     * Currently not implemented.
     */
    public function destroy(string $id)
    {
        // Delete logic would go here, but it is not currently implemented
    }
}
