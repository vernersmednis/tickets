<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_label');
    }

    // Fetches all labels from the database
    public static function getAllLabels()
    {
        return self::all(); // Returns all label records
    }

    // Creates a new label with the provided data
    public static function createLabel(array $data)
    {
        return self::create($data); // Saves the new label to the database
    }

    // Updates an existing label with the provided data
    public function updateLabel(array $data)
    {
        return $this->update($data); // Updates the current label record
    }

    // Retrieve all labels (duplicate method, can be removed if not needed)
    public static function allLabels()
    {
        return self::all(); // Returns all label records
    }
}
