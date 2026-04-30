<?php

namespace App\Http\Resources;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Answer
 */
class FeedAnswerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'answered_at' => $this->answered_at?->toIso8601String(),
            'selected_option' => $this->selected_option,
            'selected_label' => $this->selected_option === 'A'
                ? $this->question?->option_a
                : $this->question?->option_b,
            'user' => UserResource::make($this->user),
            'question' => [
                'id' => $this->question?->id,
                'prompt' => $this->question?->prompt,
                'active_on' => $this->question?->active_on?->toDateString(),
            ],
        ];
    }
}
