<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Question
 */
class QuestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewerAnswer = $this->relationLoaded('viewerAnswer') ? $this->getRelation('viewerAnswer') : null;
        $totalAnswers = (int) ($this->answers_count ?? 0);
        $optionACount = (int) ($this->option_a_answers_count ?? 0);
        $optionBCount = (int) ($this->option_b_answers_count ?? 0);

        return [
            'id' => $this->id,
            'prompt' => Question::introText(),
            'active_on' => $this->active_on?->toDateString(),
            'options' => [
                ['key' => 'A', 'label' => $this->option_a],
                ['key' => 'B', 'label' => $this->option_b],
            ],
            'has_answered' => $viewerAnswer !== null,
            'answer' => $viewerAnswer ? AnswerResource::make($viewerAnswer) : null,
            'results' => $viewerAnswer ? [
                'total_answers' => $totalAnswers,
                'option_a_count' => $optionACount,
                'option_b_count' => $optionBCount,
                'option_a_percentage' => $totalAnswers > 0 ? round(($optionACount / $totalAnswers) * 100, 1) : 0.0,
                'option_b_percentage' => $totalAnswers > 0 ? round(($optionBCount / $totalAnswers) * 100, 1) : 0.0,
            ] : null,
        ];
    }
}
