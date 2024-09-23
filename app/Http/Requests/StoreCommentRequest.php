<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check(); // Allow only authenticated users
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:500', // Validation rule for content
        ];
    }
}