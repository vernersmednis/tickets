<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;
    
    protected $fillable = ['ticket_id', 'description'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Logs the creation of a ticket. It takes a Ticket object as an argument.
    // Creates a new activity log entry with the ticket ID and a description
    public static function logTicketCreation($ticket)
    {
        // Creates an activity description using the current user's name and the ticket's title
        $activityDescription = auth()->user()->name . ' created a ticket "' . $ticket->title . '"';

        // Saves the log entry in the database
        self::create([
            'ticket_id' => $ticket->id,
            'description' => $activityDescription,
        ]);
    }

    // Logs updates to a ticket. It takes a Ticket object and a custom description of the update.
    public static function logTicketUpdate($ticket, $activityDescription)
    {
        // Saves the update log in the database with the ticket's ID and the provided description
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'description' => $activityDescription,
        ]);
    }
}
