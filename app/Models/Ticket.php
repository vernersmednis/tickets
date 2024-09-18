<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'priority', 'status', 'user_id'];

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
}
