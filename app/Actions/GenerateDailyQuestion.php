<?php

namespace App\Actions;

use App\Ai\Agents\DailyWouldYouRatherAgent;
use App\Models\Question;
use App\Models\User;
use App\Notifications\DailyQuestionPublishedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class GenerateDailyQuestion
{
    public function handle(?CarbonImmutable $date = null, bool $force = false): Question
    {
        $targetDate = ($date ?? CarbonImmutable::today('Europe/Rome'))->startOfDay();

        $existingQuestion = Question::query()
            ->whereDate('active_on', $targetDate->toDateString())
            ->first();

        if ($existingQuestion !== null && ! $force) {
            return $existingQuestion;
        }

        $response = (new DailyWouldYouRatherAgent)->prompt(
            sprintf(
                'Genera la domanda del giorno per la data %s. Deve sembrare perfetta per essere condivisa tra amici in un’app mobile.',
                $targetDate->toDateString(),
            ),
            provider: 'xai',
        );

        $questionData = $this->normalizeQuestionData([
            'option_a' => $response['option_a'],
            'option_b' => $response['option_b'],
        ]);

        return DB::transaction(function () use ($existingQuestion, $force, $questionData, $targetDate): Question {
            $question = $existingQuestion;

            if ($question !== null && $force) {
                $question->update([
                    ...$questionData,
                    'active_on' => $targetDate->toDateString(),
                ]);
            } else {
                $question = Question::query()->create([
                    ...$questionData,
                    'active_on' => $targetDate->toDateString(),
                ]);
            }

            Notification::send(
                User::query()->get(),
                (new DailyQuestionPublishedNotification($question))->afterCommit(),
            );

            return $question;
        });
    }

    /**
     * @param  array{option_a:mixed, option_b:mixed}  $questionData
     * @return array{option_a:string, option_b:string}
     */
    private function normalizeQuestionData(array $questionData): array
    {
        $optionA = trim((string) $questionData['option_a']);
        $optionB = trim((string) $questionData['option_b']);

        $optionA = preg_replace('/\s+/', ' ', $optionA) ?? $optionA;
        $optionB = preg_replace('/\s+/', ' ', $optionB) ?? $optionB;

        if ($optionA === '' || $optionB === '') {
            throw new InvalidArgumentException('The AI agent returned an invalid daily question.');
        }

        if ($optionA === $optionB) {
            throw new InvalidArgumentException('The AI agent returned two identical options.');
        }

        return [
            'option_a' => $optionA,
            'option_b' => $optionB,
        ];
    }
}
