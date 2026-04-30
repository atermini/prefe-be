<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->safe()->only([
            'name',
            'password',
        ]));

        return response()->json([
            'token' => $user->createToken($request->string('device_name')->toString())->plainTextToken,
            'user' => UserResource::make($user),
        ], 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('name', $request->string('username')->toString())
            ->first();

        if ($user === null || ! Hash::check($request->string('password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'username' => __('The provided credentials are incorrect.'),
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->string('device_name')->toString())->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }

    public function me(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
