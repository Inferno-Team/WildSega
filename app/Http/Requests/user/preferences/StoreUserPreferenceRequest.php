<?php

namespace App\Http\Requests\user\preferences;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users can store their preferences
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'tag_id' => 'required|exists:tags,id'
        ];
    }
}
