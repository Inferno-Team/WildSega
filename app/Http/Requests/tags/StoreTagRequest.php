<?php

namespace App\Http\Requests\tags;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:tags'],
        ];
    }
}
