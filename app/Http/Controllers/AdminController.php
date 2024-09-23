<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get ticket counts using the model method
        $ticketCounts = Ticket::getTicketCounts();

        // Display them
        return view('admin.dashboard', [
            'totalTickets' => $ticketCounts['total'],
            'openTickets' => $ticketCounts['open'],
            'closedTickets' => $ticketCounts['closed'],
        ]);
    }
}
