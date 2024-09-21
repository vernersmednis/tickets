<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_category');
    }

    // Fetches all categories from the database
    public static function getAllCategories()
    {
        return self::all(); // Returns all category records
    }

    // Creates a new category with the provided data
    public static function createCategory(array $data)
    {
        return self::create($data); // Saves the new category to the database
    }

    // Updates an existing category with the provided data
    public function updateCategory(array $data)
    {
        return $this->update($data); // Updates the current category record
    }

    // Retrieve all categories (duplicate method, can be removed if not needed)
    public static function allCategories()
    {
        return self::all(); // Returns all category records
    }
}
