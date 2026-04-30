<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RespondToFriendRequestRequest;
use App\Http\Requests\Api\StoreFriendRequest;
use App\Http\Resources\FriendshipResource;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FriendshipController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $friendships = Friendship::query()
            ->with(['requester', 'addressee'])
            ->where(function ($query) use ($user): void {
                $query->where('requester_id', $user->id)
                    ->orWhere('addressee_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json([
            'friends' => FriendshipResource::collection(
                $friendships->where('status', Friendship::STATUS_ACCEPTED)->values()
            ),
            'incoming_requests' => FriendshipResource::collection(
                $friendships
                    ->where('status', Friendship::STATUS_PENDING)
                    ->where('addressee_id', $user->id)
                    ->values()
            ),
            'outgoing_requests' => FriendshipResource::collection(
                $friendships
                    ->where('status', Friendship::STATUS_PENDING)
                    ->where('requester_id', $user->id)
                    ->values()
            ),
        ]);
    }

    public function store(StoreFriendRequest $request): JsonResponse
    {
        $user = $request->user();
        $friend = User::query()
            ->where('email', $request->string('friend_email')->toString())
            ->firstOrFail();

        if ($user->is($friend)) {
            throw new HttpException(422, 'You cannot send a friend request to yourself.');
        }

        $existingFriendship = Friendship::query()
            ->where(function ($query) use ($user, $friend): void {
                $query->where('requester_id', $user->id)
                    ->where('addressee_id', $friend->id);
            })
            ->orWhere(function ($query) use ($user, $friend): void {
                $query->where('requester_id', $friend->id)
                    ->where('addressee_id', $user->id);
            })
            ->first();

        if ($existingFriendship !== null) {
            throw new ConflictHttpException('A friendship already exists between these users.');
        }

        $friendship = Friendship::query()->create([
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_PENDING,
        ]);

        return response()->json([
            'data' => FriendshipResource::make($friendship->load(['requester', 'addressee']))->resolve($request),
        ], 201);
    }

    public function update(
        RespondToFriendRequestRequest $request,
        Friendship $friendship
    ): JsonResponse {
        if ($friendship->addressee_id !== $request->user()->id) {
            throw new HttpException(403, 'You cannot manage this friend request.');
        }

        if ($friendship->status !== Friendship::STATUS_PENDING) {
            throw new ConflictHttpException('This friend request has already been handled.');
        }

        $friendship->update([
            'status' => $request->string('status')->toString(),
            'responded_at' => now(),
        ]);

        return response()->json([
            'data' => FriendshipResource::make($friendship->load(['requester', 'addressee']))->resolve($request),
        ]);
    }
}
