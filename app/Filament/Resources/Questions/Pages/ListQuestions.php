<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Actions\GenerateDailyQuestion;
use App\Filament\Resources\Questions\QuestionResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateDailyQuestion')
                ->label('Genera domanda di oggi')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Genera domanda di oggi')
                ->modalDescription('Verrà chiamata l\'AI per generare la domanda del giorno. Se esiste già una domanda per oggi verrà sovrascritta.')
                ->modalSubmitActionLabel('Genera')
                ->action(function (): void {
                    try {
                        (new GenerateDailyQuestion)->handle(force: true);

                        Notification::make()
                            ->title('Domanda generata con successo')
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Errore durante la generazione')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
