<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\user\UpdateUserRequest;

/**
 * @group User Management
 * @authenticated
 * @middleware auth:sanctum
 */
class UserController extends Controller
{
    /**
     * Retrieve the authenticated user's profile.
     */
    public function profile()
    {
        return response()->json([
            'user' => UserResource::make(Auth::user()),
            'message' => 'User profile retrieved successfully.',
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateUserRequest $request)
    {
        // Only admin can update the user data
        $validated = $request->validated();
        $user = Auth::user();

        // Update the user with the validated data
        $user->update($validated);

        return response()->json([
            'message' => 'User profile updated successfully.',
            'user' => new UserResource($user),
        ]);
    }
}
