<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Feed</h1>
        <span class="text-sm text-neutral-500">{{ $answers->count() }} risposte</span>
    </div>

    @if ($answers->isEmpty())
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🤫</div>
            <h2 class="text-xl font-semibold text-neutral-300">Nessuna risposta ancora</h2>
            <p class="mt-2 text-neutral-500">Sii il primo a rispondere alla domanda di oggi.</p>
            <a href="{{ route('question') }}" wire:navigate
               class="inline-block mt-4 text-sm text-violet-400 font-medium hover:text-violet-300">
                Vai alla domanda &rarr;
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($answers as $answer)
                <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-violet-900 flex items-center justify-center shrink-0">
                                <span class="text-sm font-bold text-violet-300">
                                    {{ strtoupper(substr($answer->user?->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-white text-sm">{{ $answer->user?->name }}</p>
                                <p class="text-xs text-neutral-500">{{ $answer->answered_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($answer->question)
                        <div class="mt-4 pl-12 space-y-1.5">
                            <p class="text-xs text-neutral-500 mb-2">{{ $answer->question->introText() }}</p>
                            @foreach (['A' => $answer->question->option_a, 'B' => $answer->question->option_b] as $opt => $label)
                                <div class="flex items-center gap-2 text-sm
                                            {{ $answer->selected_option === $opt ? 'font-semibold text-violet-400' : 'text-neutral-600' }}">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                                 {{ $answer->selected_option === $opt ? 'bg-violet-600 text-white' : 'bg-neutral-800 text-neutral-600' }}">
                                        {{ $opt }}
                                    </span>
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
