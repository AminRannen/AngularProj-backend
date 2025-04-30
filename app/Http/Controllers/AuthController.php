<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|integer|min:0|max:1', // Make role optional
        ]);

        $userData = [
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->has('role') ? $request->role : 1, // Default to 1 if not provided
        ];

        $user = User::create($userData);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Login a user and return a JWT
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'user' => JWTAuth::user(),
            'token' => $token,
        ]);
    }
    
    // Log out the user
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Get the authenticated user's details
    public function me()
    {
        return response()->json(Auth::user());
    }
}