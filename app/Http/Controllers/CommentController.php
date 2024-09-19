<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Create the comment
        $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        // Redirect back to the ticket details page
        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Comment added successfully.');
    }
}
