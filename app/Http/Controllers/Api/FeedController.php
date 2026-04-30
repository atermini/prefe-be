<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedAnswerResource;
use App\Models\Answer;
use App\Models\Friendship;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $friendIds = Friendship::query()
            ->accepted()
            ->where(function ($query) use ($user): void {
                $query->where('requester_id', $user->id)
                    ->orWhere('addressee_id', $user->id);
            })
            ->get()
            ->map(fn (Friendship $friendship): int => $friendship->requester_id === $user->id
                ? $friendship->addressee_id
                : $friendship->requester_id
            )
            ->values();

        $answers = Answer::query()
            ->with(['question', 'user'])
            ->where('is_shared', true)
            ->whereIn('user_id', $friendIds)
            ->latest('answered_at')
            ->latest('id')
            ->limit(50)
            ->get();

        return FeedAnswerResource::collection($answers);
    }
}
