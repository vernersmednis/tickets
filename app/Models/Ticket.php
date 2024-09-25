<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'priority', 'status', 'user_agent_id'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function userAgent()
    {
        return $this->belongsTo(User::class, 'user_agent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ticket_category');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'ticket_label');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    
    // Method to create a comment associated with the ticket
    public function addComment(array $data, $userId)
    {
        return $this->comments()->create([
            'user_id' => $userId,  // Associate the comment with the user
            'content' => $data['content'],  // Comment content
        ]);
    }

    // Get counts of total, opened, and closed tickets for admin dashboard view
    public static function getTicketCounts()
    {
        return [
            'total' => self::count(),  // Total number of tickets
            'open' => self::where('status', 'open')->count(),  // Count of open tickets
            'closed' => self::where('status', 'closed')->count(),  // Count of closed tickets
        ];
    }

    // Scope for filtering tickets based on user role and provided filters
    public function scopeFilter(Builder $query, $user, array $filters)
    {
        // Eager load categories to avoid N+1 problem
        $query->with('categories');
        
        // If the user is not an admin, filter tickets by user ID
        if ($user->role !== 'admin') {
            $query->where('user_agent_id', $user->id);
        }

        // Apply additional filters for status, priority, and categories if provided
        return $query
            ->when($filters['status'] ?? null, function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when($filters['priority'] ?? null, function ($query) use ($filters) {
                $query->where('priority', $filters['priority']);
            })
            ->when($filters['category_id'] ?? null, function ($query) use ($filters) {
                $query->whereHas('categories');
            });
    }

    // Create a new ticket and sync associated labels and categories
    public static function createTicket(array $data, $userAgentId)
    {
        // Create the ticket with the necessary fields
        $ticket = self::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => $data['status'],
            'user_agent_id' => $userAgentId,
        ]);

        // Sync labels if provided
        if (isset($data['labels']) && !empty($data['labels'])) {
            $ticket->labels()->sync($data['labels']);
        }

        // Sync categories if provided
        if (isset($data['categories']) && !empty($data['categories'])) {
            $ticket->categories()->sync($data['categories']); // Fixed to sync categories instead of labels
        }

        return $ticket;  // Return the created ticket
    }

    // Load the ticket's comments and activity logs in the desired order
    public function loadWithRelations()
    {
        return $this->load(['comments', 'activityLogs' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Order activity logs by created_at in descending order
        }]);
    }

    // Update ticket details and log changes
    public function updateTicket($validatedData)
    {
        // Get the original attributes before updating
        $originalAttributes = $this->getOriginal();

        // Update ticket details with validated data
        $this->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'priority' => $validatedData['priority'],
            'status' => $validatedData['status']
        ]);

        if(isset($validatedData['user_agent_id'])) {
            $this->update([ 'user_agent_id' => $validatedData['user_agent_id'] ]); // Assuming this refers to the agent ID)
        } 

        // Sync labels if they exist
        if (isset($validatedData['labels']) && !empty($validatedData['labels'])) {
            $labelSync = $this->labels()->sync($validatedData['labels']);
        }
        // Sync categories if they exist
        if (isset($validatedData['categories']) && !empty($validatedData['categories'])) {
            $categorySync = $this->categories()->sync($validatedData['categories']);
        }

        // Build an activity description based on changed attributes
        $changedAttributes = [];
        foreach (['title', 'description', 'priority', 'status'] as $attribute) {
            if ($originalAttributes[$attribute] !== $this->$attribute) {
                $changedAttributes[] = ucfirst($attribute) . ' changed from "' . $originalAttributes[$attribute] . '" to "' . $this->$attribute . '"';
            }
        }

        // Fetch added and removed label names
        if (isset($labelSync['attached'])) {
            $addedLabels = \App\Models\Label::whereIn('id', $labelSync['attached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Labels added: "' . implode(', ', $addedLabels) . '"';
        }
        if (isset($labelSync['detached'])) {
            $removedLabels = \App\Models\Label::whereIn('id', $labelSync['detached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Labels removed: "' . implode(', ', $removedLabels) . '"';
        }

        // Fetch added and removed category names
        if (isset($categorySync['attached'])) {
            $addedCategories = \App\Models\Category::whereIn('id', $categorySync['attached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Categories added: "' . implode(', ', $addedCategories) . '"';
        }
        if (isset($categorySync['detached'])) {
            $removedCategories = \App\Models\Category::whereIn('id', $categorySync['detached'])->pluck('name')->toArray();
            $changedAttributes[] = 'Categories removed: "' . implode(', ', $removedCategories) . '"';
        }

        // Construct the final activity description
        $activityDescription = Auth::user()->name . ' edited a ticket "' . $this->title . '" with changes: ' . implode(', ', $changedAttributes);

        // Log the activity
        ActivityLog::logTicketUpdate($this, $activityDescription);
    }
}
