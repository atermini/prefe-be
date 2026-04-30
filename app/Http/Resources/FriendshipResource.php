<?php

namespace App\Http\Resources;

use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Friendship
 */
class FriendshipResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewerId = $request->user()?->id;
        $isOutgoing = $viewerId === $this->requester_id;
        $otherUser = $isOutgoing ? $this->addressee : $this->requester;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'direction' => $isOutgoing ? 'outgoing' : 'incoming',
            'responded_at' => $this->responded_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'user' => UserResource::make($otherUser),
        ];
    }
}
