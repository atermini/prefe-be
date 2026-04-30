<?php

namespace App\Livewire\Pages;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public ?Question $question = null;

    public ?Answer $userAnswer = null;

    #[Validate('required|in:A,B')]
    public string $selectedOption = '';

    public function mount(): void
    {
        $this->loadNextUnanswered();
    }

    public function answer(): void
    {
        if (! $this->question || $this->userAnswer) {
            return;
        }

        $this->validate();

        $this->userAnswer = Answer::create([
            'question_id' => $this->question->id,
            'user_id' => Auth::id(),
            'selected_option' => $this->selectedOption,
            'is_shared' => true,
            'answered_at' => now(),
        ]);
    }

    public function next(): void
    {
        $this->selectedOption = '';
        $this->loadNextUnanswered();
    }

    private function loadNextUnanswered(): void
    {
        $answeredIds = Answer::query()
            ->where('user_id', Auth::id())
            ->pluck('question_id');

        $this->question = Question::query()
            ->whereDate('active_on', '<=', today())
            ->whereNotIn('id', $answeredIds)
            ->oldest('active_on')
            ->oldest('id')
            ->first();

        $this->userAnswer = null;
    }

    public function render()
    {
        $stats = null;
        $feed = null;
        $unansweredCount = null;

        if ($this->userAnswer) {
            $total = $this->question->answers()->count();
            $countA = $this->question->answers()->where('selected_option', 'A')->count();
            $countB = $this->question->answers()->where('selected_option', 'B')->count();

            $stats = [
                'total' => $total,
                'a' => $countA,
                'b' => $countB,
                'pct_a' => $total > 0 ? (int) round($countA / $total * 100) : 0,
                'pct_b' => $total > 0 ? (int) round($countB / $total * 100) : 0,
            ];

            $answeredIds = Answer::query()
                ->where('user_id', Auth::id())
                ->pluck('question_id');

            $unansweredCount = Question::query()
                ->whereDate('active_on', '<=', today())
                ->whereNotIn('id', $answeredIds)
                ->count();
        }

        if ($this->question === null) {
            $feed = Answer::query()
                ->with(['question', 'user'])
                ->where('is_shared', true)
                ->latest('answered_at')
                ->latest('id')
                ->limit(50)
                ->get();
        }

        return view('livewire.pages.home', compact('stats', 'feed', 'unansweredCount'));
    }
}
