<?php

namespace App\Http\Requests\user\preferences;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DestroyUserPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users can delete their preferences
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'tag_id' => 'required|exists:tags,id', // The tag must exist
        ];
    }
}
