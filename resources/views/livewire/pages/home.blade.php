<div class="max-w-xl mx-auto">

    @if (!$question)
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🌙</div>
            <h2 class="text-xl font-semibold text-neutral-300">Nessuna domanda per oggi</h2>
            <p class="mt-2 text-neutral-500">Torna domani per una nuova sfida.</p>
        </div>

    @elseif (!$userAnswer)
        {{-- Deve ancora rispondere --}}
        <div class="text-center mb-8">
            <p class="text-sm font-medium text-violet-400 uppercase tracking-wider mb-2">Domanda di oggi</p>
            <h1 class="text-2xl font-bold text-white leading-snug">{{ $question->introText() }}</h1>
        </div>

        <form wire:submit="answer" class="space-y-4">
            @error('selectedOption')
                <p class="text-sm text-red-400 text-center">{{ $message }}</p>
            @enderror

            <button type="button" wire:click="$set('selectedOption', 'A')"
                    class="w-full text-left p-5 rounded-2xl border-2 transition-all
                           {{ $selectedOption === 'A'
                               ? 'border-violet-500 bg-violet-950'
                               : 'border-neutral-800 bg-neutral-900 hover:border-neutral-600' }}">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0
                                 {{ $selectedOption === 'A' ? 'bg-violet-500 text-white' : 'bg-neutral-800 text-neutral-400' }}">
                        A
                    </span>
                    <span class="font-medium {{ $selectedOption === 'A' ? 'text-white' : 'text-neutral-300' }}">{{ $question->option_a }}</span>
                </div>
            </button>

            <button type="button" wire:click="$set('selectedOption', 'B')"
                    class="w-full text-left p-5 rounded-2xl border-2 transition-all
                           {{ $selectedOption === 'B'
                               ? 'border-violet-500 bg-violet-950'
                               : 'border-neutral-800 bg-neutral-900 hover:border-neutral-600' }}">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0
                                 {{ $selectedOption === 'B' ? 'bg-violet-500 text-white' : 'bg-neutral-800 text-neutral-400' }}">
                        B
                    </span>
                    <span class="font-medium {{ $selectedOption === 'B' ? 'text-white' : 'text-neutral-300' }}">{{ $question->option_b }}</span>
                </div>
            </button>

            <button type="submit"
                    class="w-full bg-violet-600 text-white font-semibold py-3 rounded-xl hover:bg-violet-500 transition-colors focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-neutral-950 disabled:opacity-30 disabled:cursor-not-allowed"
                    @if(!$selectedOption) disabled @endif>
                <span wire:loading.remove>Rispondi</span>
                <span wire:loading>Invio...</span>
            </button>
        </form>

    @else
        {{-- Ha risposto: statistiche + feed --}}
        <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-6 space-y-4 mb-8">
            <div class="text-center">
                <p class="text-xs text-violet-400 uppercase tracking-wider mb-1">Domanda di oggi</p>
                <p class="text-sm text-neutral-300">{{ $question->introText() }}</p>
                <p class="text-xs text-neutral-500 mt-1">
                    Hai scelto
                    <span class="text-violet-400 font-semibold">
                        {{ $userAnswer->selected_option === 'A' ? $question->option_a : $question->option_b }}
                    </span>
                </p>
            </div>

            @if ($stats && $stats['total'] > 0)
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-neutral-300 font-medium">{{ $question->option_a }}</span>
                            <span class="text-neutral-500">{{ $stats['pct_a'] }}%</span>
                        </div>
                        <div class="h-2.5 bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $userAnswer->selected_option === 'A' ? 'bg-violet-500' : 'bg-neutral-600' }}"
                                 style="width: {{ $stats['pct_a'] }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-neutral-300 font-medium">{{ $question->option_b }}</span>
                            <span class="text-neutral-500">{{ $stats['pct_b'] }}%</span>
                        </div>
                        <div class="h-2.5 bg-neutral-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $userAnswer->selected_option === 'B' ? 'bg-violet-500' : 'bg-neutral-600' }}"
                                 style="width: {{ $stats['pct_b'] }}%"></div>
                        </div>
                    </div>
                </div>
                <p class="text-center text-xs text-neutral-600">{{ $stats['total'] }} {{ $stats['total'] === 1 ? 'risposta' : 'risposte' }} totali</p>
            @endif
        </div>

        {{-- Feed --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-white">Feed</h2>
            <span class="text-sm text-neutral-500">{{ $feed?->count() ?? 0 }} risposte</span>
        </div>

        @if ($feed && $feed->isNotEmpty())
            <div class="space-y-4">
                @foreach ($feed as $answer)
                    <div class="bg-neutral-900 rounded-2xl border border-neutral-800 p-5">
                        <div class="flex items-center gap-3 mb-4">
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

                        @if ($answer->question)
                            <div class="space-y-1.5">
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
        @else
            <div class="text-center py-10">
                <p class="text-neutral-500 text-sm">Ancora nessun'altra risposta condivisa.</p>
            </div>
        @endif
    @endif

</div>
