<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodayQuestionController extends Controller
{
    public function show(Request $request): QuestionResource
    {
        $question = Question::query()
            ->forDate(today()->toDateString())
            ->first();

        if ($question === null) {
            throw new NotFoundHttpException('No question is scheduled for today.');
        }

        $viewerAnswer = $question->answers()
            ->whereBelongsTo($request->user())
            ->with('question')
            ->first();

        $question->setRelation('viewerAnswer', $viewerAnswer);

        if ($viewerAnswer !== null) {
            $question->loadCount([
                'answers',
                'answers as option_a_answers_count' => fn ($query) => $query->where('selected_option', 'A'),
                'answers as option_b_answers_count' => fn ($query) => $query->where('selected_option', 'B'),
            ]);
        }

        return QuestionResource::make($question);
    }
}
