<?php

namespace App\Actions;

use App\Ai\Agents\DailyWouldYouRatherAgent;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\DailyQuestionPublishedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class GenerateDailyQuestion
{
    /**
     * Probability (0-100) of including subjects in the prompt when at least one is available.
     */
    private const SUBJECT_INCLUSION_PROBABILITY = 70;

    /**
     * Maximum number of subjects to include in a single prompt.
     */
    private const MAX_SUBJECTS_PER_PROMPT = 3;

    /**
     * @param  Collection<int, Subject>|null  $subjects  When null, subjects are picked automatically.
     */
    public function handle(
        ?CarbonImmutable $date = null,
        ?User $createdBy = null,
        ?Collection $subjects = null,
    ): Question {
        $targetDate = ($date ?? CarbonImmutable::today('Europe/Rome'))->startOfDay();

        $promptSubjects = $subjects ?? $this->selectSubjectsForPrompt();

        $response = (new DailyWouldYouRatherAgent)->prompt(
            $this->buildPrompt($targetDate, $promptSubjects),
            provider: 'xai',
        );

        $questionData = $this->normalizeQuestionData([
            'option_a' => $response['option_a'],
            'option_b' => $response['option_b'],
        ]);

        return DB::transaction(function () use ($questionData, $targetDate, $createdBy): Question {
            $question = Question::query()->create([
                ...$questionData,
                'active_on' => $targetDate->toDateString(),
                'user_id' => $createdBy?->id,
            ]);

            Notification::send(
                User::query()->get(),
                (new DailyQuestionPublishedNotification($question))->afterCommit(),
            );

            return $question;
        });
    }

    /**
     * Randomly decide whether to involve subjects, and pick a small set if so.
     *
     * @return Collection<int, Subject>
     */
    public function selectSubjectsForPrompt(): Collection
    {
        $available = Subject::query()->count();

        if ($available === 0) {
            return collect();
        }

        if (random_int(1, 100) > self::SUBJECT_INCLUSION_PROBABILITY) {
            return collect();
        }

        $take = random_int(1, min(self::MAX_SUBJECTS_PER_PROMPT, $available));

        return Subject::query()->inRandomOrder()->limit($take)->get();
    }

    /**
     * @param  Collection<int, Subject>  $subjects
     */
    private function buildPrompt(CarbonImmutable $targetDate, Collection $subjects): string
    {
        $base = sprintf(
            'Genera la domanda del giorno per la data %s. Deve sembrare perfetta per essere condivisa tra amici in un\'app mobile.',
            $targetDate->toDateString(),
        );

        if ($subjects->isEmpty()) {
            return $base;
        }

        $lines = $subjects
            ->map(fn (Subject $subject): string => '- '.$subject->promptLine())
            ->implode("\n");

        return $base."\n\nSoggetti da coinvolgere (usa nome e contesto tra parentesi):\n".$lines;
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
