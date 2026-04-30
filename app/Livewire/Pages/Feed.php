<?php

namespace App\Livewire\Pages;

use App\Models\Answer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Feed extends Component
{
    public function render()
    {
        $answers = Answer::query()
            ->with(['question', 'user'])
            ->where('is_shared', true)
            ->latest('answered_at')
            ->latest('id')
            ->limit(50)
            ->get();

        return view('livewire.pages.feed', compact('answers'));
    }
}
