<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreDeviceTokenRequest;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;

class DeviceTokenController extends Controller
{
    public function store(StoreDeviceTokenRequest $request): JsonResponse
    {
        $deviceToken = DeviceToken::query()->updateOrCreate(
            ['token' => $request->string('token')->toString()],
            [
                'user_id' => $request->user()->id,
                'platform' => $request->string('platform')->toString(),
                'last_used_at' => now(),
            ],
        );

        return response()->json([
            'data' => [
                'id' => $deviceToken->id,
                'platform' => $deviceToken->platform,
                'last_used_at' => $deviceToken->last_used_at?->toIso8601String(),
            ],
        ], 201);
    }
}
