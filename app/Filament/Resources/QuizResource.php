<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Quizzes';
    protected static ?string $title = 'Quiz';
    protected static ?string $pluralModelLabel = 'Quizzes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make( 'name' )->label( 'Name' )->sortable()->searchable(),
                TextColumn::make( 'questions_count' )->label( 'Fragen' )->counts( 'questions' )->sortable(),
                TextColumn::make( 'created_at' )->label( 'Erstellt am' )
                    ->dateTime( 'd.m.Y H:i:s' )
                    ->sortable()
                    ->searchable(),
                TextColumn::make( 'updated_at' )->label( 'Zuletzt geändert am' )
                    ->dateTime( 'd.m.Y H:i:s' )
                    ->sortable()
                    ->searchable(),
                IconColumn::make('deleted_at')
                    ->label('Status')
                    ->options([
                        'heroicon-o-badge-check',
                        'heroicon-o-trash' => fn ($state, $record): bool => $record->deleted_at != null
                    ])
                    ->colors([
                        'secondary',
                        'danger' => fn ($state, $record): bool => $record->deleted_at != null,
                    ])
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
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
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return Quiz::query()
            ->where('creator_id', $user->id)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

//        return parent::getEloquentQuery()
//            ->withoutGlobalScopes([
//                SoftDeletingScope::class,
//            ]);
    }
}
