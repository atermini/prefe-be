<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedAnswerResource;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $answers = Answer::query()
            ->with(['question', 'user'])
            ->where('is_shared', true)
            ->latest('answered_at')
            ->latest('id')
            ->limit(50)
            ->get();

        return FeedAnswerResource::collection($answers);
    }
}
