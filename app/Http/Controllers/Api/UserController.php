<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Eloquent\EloquentGenericCrudRepository;
use App\Http\Responses\ApiResponse;

class UserController extends Controller
{
    public function __construct(private EloquentGenericCrudRepository $repo = new EloquentGenericCrudRepository(new User())) {}

    /**
     * Register a new user and return API token
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = $this->repo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(
            new ApiResponse(
                data: ['user' => $user, 'token' => $token],
                message: 'User registered successfully'
            ),
            201
        );
    }

    /**
     * Login user and return API token
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(
                new ApiResponse(
                    data: null,
                    message: 'Invalid credentials',
                    status: false
                ),
                401
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(
            new ApiResponse(
                data: ['user' => $user, 'token' => $token],
                message: 'Logged in successfully'
            )
        );
    }

    /**
     * Logout user by deleting current token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(
            new ApiResponse(
                data: null,
                message: 'Logged out successfully'
            )
        );
    }

    /**
     * Get authenticated user details
     */
    public function me(Request $request)
    {
        return response()->json(
            new ApiResponse(
                data: ['user' => $request->user()],
                message: 'Authenticated user retrieved successfully'
            )
        );
    }
}
