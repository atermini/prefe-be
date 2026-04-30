<?php

namespace App\Filament\Resources\Answers;

use App\Filament\Resources\Answers\Pages\CreateAnswer;
use App\Filament\Resources\Answers\Pages\EditAnswer;
use App\Filament\Resources\Answers\Pages\ListAnswers;
use App\Filament\Resources\Answers\Pages\ViewAnswer;
use App\Filament\Resources\Answers\Schemas\AnswerForm;
use App\Filament\Resources\Answers\Schemas\AnswerInfolist;
use App\Filament\Resources\Answers\Tables\AnswersTable;
use App\Models\Answer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AnswerResource extends Resource
{
    protected static ?string $model = Answer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $navigationLabel = 'Risposte';

    protected static ?string $modelLabel = 'Risposta';

    protected static ?string $pluralModelLabel = 'Risposte';

    protected static string|\UnitEnum|null $navigationGroup = 'Attività';

    public static function form(Schema $schema): Schema
    {
        return AnswerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AnswerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnswersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnswers::route('/'),
            'create' => CreateAnswer::route('/create'),
            'view' => ViewAnswer::route('/{record}'),
            'edit' => EditAnswer::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
