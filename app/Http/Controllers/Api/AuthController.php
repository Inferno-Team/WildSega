<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'the prodived creadentials are incorrect'], 400);
        }
        $token = $user->createToken('login-token')->plainTextToken;

        return response()->json([
            'message' => 'logged in successfully.',
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password'])
        ]);
        $token = $user->createToken('register-token')->plainTextToken;

        return response()->json([
            'message' => 'user created successfully.',
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }
}
