<?php

namespace App\Http\Resources;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Answer
 */
class AnswerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $selectedLabel = null;

        if ($this->relationLoaded('question')) {
            $selectedLabel = $this->selected_option === 'A'
                ? $this->question?->option_a
                : $this->question?->option_b;
        }

        return [
            'id' => $this->id,
            'selected_option' => $this->selected_option,
            'selected_label' => $selectedLabel,
            'is_shared' => $this->is_shared,
            'answered_at' => $this->answered_at?->toIso8601String(),
        ];
    }
}
