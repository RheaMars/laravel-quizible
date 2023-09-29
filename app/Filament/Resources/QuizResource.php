<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Quiz;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\QuizResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Filament\Resources\QuizResource\RelationManagers\QuestionsRelationManager;

class QuizResource extends Resource {
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Quizzes';
    protected static ?string $title = 'Quiz';
    protected static ?string $pluralModelLabel = 'Quizzes';

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            Section::make( 'Quiz' )->label( 'Quiz' )->collapsible()->schema( [
                TextInput::make( 'name' )->label( 'Name' )->required(),
            ] ),
            Section::make( 'Fragen' )->collapsible()->schema( [
                Repeater::make( 'questions' )->label( 'Frage' )->relationship()->orderable( 'sort' )->collapsible()->schema( [
                    Select::make( 'type' )
                    ->label( 'Typ' )
                    ->required()
                    ->options( [
                        'multiple-choice' => 'Multiple-Choice',
                        'true-false' => 'Wahr-Falsch',
                        'short-answer' => 'Kurz-Antwort',
                    ] ),
                    TextInput::make( 'content' )
                    ->label( 'Frage' )
                    ->required()
                    ->columnSpan( 3 ),
                    Repeater::make( 'answers' )->label( 'Antworten' )->relationship()->orderable( 'sort' )->collapsible()->schema( [
                        TextInput::make( 'content' )
                        ->label( 'Antwort' )
                        ->required()
                        ->columnSpan( 3 ),
                        Toggle::make( 'is_correct' )->label( 'richtige Antwort' )->inline()->columnSpan( 1 )
                    ] )->columnSpan( 4 )->createItemButtonLabel( 'Antwort hinzufügen' )
                ] )->columns( 4 )->createItemButtonLabel( 'Frage hinzufügen' ),
            ] ),
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->columns( [
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
            IconColumn::make( 'deleted_at' )
            ->label( 'Status' )
            ->options( [
                'heroicon-o-check-badge',
                'heroicon-o-trash' => fn ( $state, $record ): bool => $record->deleted_at != null
            ] )
            ->colors( [
                'secondary',
                'danger' => fn ( $state, $record ): bool => $record->deleted_at != null,
            ] )
            ->sortable()
        ] )
        ->filters( [
            Tables\Filters\TrashedFilter::make(),
        ] )
        ->actions( [
            Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\DeleteBulkAction::make(),
            Tables\Actions\ForceDeleteBulkAction::make(),
            Tables\Actions\RestoreBulkAction::make(),
        ] );
    }

    public static function getRelations(): array {
        return [
            // QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListQuizzes::route( '/' ),
            'create' => Pages\CreateQuiz::route( '/create' ),
            'edit' => Pages\EditQuiz::route( '/{record}/edit' ),
        ];
    }

    public static function getEloquentQuery(): Builder {
        $user = auth()->user();
        return Quiz::query()
        ->where( 'creator_id', $user->id )
        ->withoutGlobalScopes( [
            SoftDeletingScope::class,
        ] );

        //        return parent::getEloquentQuery()
        //            ->withoutGlobalScopes( [
        //                SoftDeletingScope::class,
        // ] );
    }
}
