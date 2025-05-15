<?php

namespace App\Http\Requests\user;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow admins to update user information
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->route('user'),
            'notification_range_km' => 'nullable|integer',
        ];
    }
}
