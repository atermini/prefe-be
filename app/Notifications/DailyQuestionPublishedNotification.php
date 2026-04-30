<?php

namespace App\Notifications;

use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DailyQuestionPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Question $question)
    {
        $this->afterCommit();
        $this->onConnection(config('queue.default'));
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'daily-question-published',
            'title' => 'Nuova domanda del giorno',
            'body' => $this->question->fullText(),
            'question_id' => $this->question->id,
            'active_on' => $this->question->active_on?->toDateString(),
            'prompt' => $this->question->introText(),
            'option_a' => $this->question->option_a,
            'option_b' => $this->question->option_b,
        ];
    }
}
