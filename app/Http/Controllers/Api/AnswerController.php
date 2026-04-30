<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAnswerRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, Question $question): JsonResponse
    {
        if (! $question->active_on?->isToday()) {
            throw new NotFoundHttpException('This question is not available today.');
        }

        $user = $request->user();

        if ($question->answers()->whereBelongsTo($user)->exists()) {
            throw new ConflictHttpException('You have already answered this question.');
        }

        $answer = $question->answers()->create([
            'user_id' => $user->id,
            'selected_option' => $request->string('selected_option')->toString(),
            'is_shared' => $request->boolean('is_shared'),
            'answered_at' => now(),
        ]);

        $question->setRelation('viewerAnswer', $answer->load('question'));
        $question->loadCount([
            'answers',
            'answers as option_a_answers_count' => fn ($query) => $query->where('selected_option', 'A'),
            'answers as option_b_answers_count' => fn ($query) => $query->where('selected_option', 'B'),
        ]);

        return response()->json([
            'data' => QuestionResource::make($question)->resolve($request),
        ], 201);
    }
}
