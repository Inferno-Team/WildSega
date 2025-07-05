<?php

namespace App\Http\Controllers\API\auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Group;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Validation\ValidationException;

/**
 * @group Authenticating requests
 */
class AuthController extends Controller
{

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'notification_range_km' => $data['notification_range_km'] ?? 10,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    /**
     * Login a user.
     * @unauthenticated
     * @bodyParam email string required User email
     * @bodyParam password string required User password
     * @responseField token The authentication token
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }
}
