<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreCommentRequest; // Import the request class
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Ticket $ticket)
    {
        // Get validated data from the request
        $validatedData = $request->validated();

        // Create the comment using the model method
        $ticket->addComment($validatedData, Auth::id());

        // Redirect back to the ticket details page
        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Comment added successfully.');
    }
}
