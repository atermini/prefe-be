<?php

namespace App\Livewire;

use App\Models\Answer;
use App\Models\Friendship;
use App\Models\Question;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $todayQuestion = Question::query()
            ->whereDate('active_on', today())
            ->first();

        $todayAnswers = $todayQuestion?->answers()->count() ?? 0;
        $sharedAnswers = Answer::query()->where('is_shared', true)->count();
        $acceptedFriendships = Friendship::query()
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->count();
        $pendingFriendships = Friendship::query()
            ->where('status', Friendship::STATUS_PENDING)
            ->count();

        return [
            Stat::make('Utenti', (string) User::query()->count())
                ->description('Account registrati')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Domanda di oggi', $todayQuestion?->prompt ?? 'Non programmata')
                ->description("Risposte oggi: {$todayAnswers}")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($todayQuestion ? 'success' : 'warning'),
            Stat::make('Risposte condivise', (string) $sharedAnswers)
                ->description('Visibili nel feed amici')
                ->descriptionIcon('heroicon-m-share')
                ->color('info'),
            Stat::make('Amicizie accettate', (string) $acceptedFriendships)
                ->description("Richieste pendenti: {$pendingFriendships}")
                ->descriptionIcon('heroicon-m-heart')
                ->color($pendingFriendships > 0 ? 'warning' : 'success'),
        ];
    }
}
