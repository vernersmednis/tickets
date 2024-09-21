<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CheckUserRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        
        if ($user && in_array($user->role, $roles)) {
            // Access the {ticket} parameter
            $ticket = $request->route('ticket');
            if($ticket)
            {   
                // Example: Check if the user is authorized to edit or create this ticket
                if ($ticket->user_agent_id === Auth::user()->id || $user->role === 'admin') {
                    return $next($request);
                }
            } else {
                return $next($request);
            }
        }

        // Show a 403 Forbidden page
        abort(403);
    }
}
