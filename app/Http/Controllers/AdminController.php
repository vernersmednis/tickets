<?php
// AdminController.php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get ticket counts
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        return view('admin.dashboard', compact('totalTickets', 'openTickets', 'closedTickets'));
    }

    public function logs()
    {

    }
}
