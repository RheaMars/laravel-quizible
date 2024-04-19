<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Models\Course;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Flashcard;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FlashcardResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FlashcardResource extends Resource {
    protected static ?string $model = Flashcard::class;

    protected static ?string $navigationIcon = 'bi-card-text';
    protected static ?string $navigationLabel = 'Karteikarten verwalten';
    protected static ?string $title = 'Karteikarte';
    protected static ?string $pluralModelLabel = 'Karteikarten';
    protected static ?string $modelLabel = 'Karteikarte';

    protected static ?string $navigationGroup = 'Karteikarten';

    protected static ?int $navigationSort = 1;

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            Select::make('course_id')->label('Fach')
                ->relationship(
                    name: 'course',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Auth::user()),
                )
                ->createOptionModalHeading('Fach erstellen')
                ->createOptionForm([
                    TextInput::make('course_name')
                        ->label('Name')
                        ->required(),
                    ])
                ->createOptionUsing(function (array $data, Course $course) {
                    $course->user_id = Auth::user()->id;
                    $course->name = $data['course_name'];
                    $course->save();
                })
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('category_id', '');
                })
                ->required(),
            Select::make('category_id')->label('Kategorie')
                ->relationship(
                    name: 'category',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('course_id', $get('course_id')),
                )
                ->createOptionModalHeading('Kategorie erstellen')
                ->createOptionForm([
                    TextInput::make('category_name')
                        ->label('Name')
                        ->required(),
                ])
                ->createOptionUsing(function (array $data, Category $category, Get $get) {
                    $category->user_id = Auth::user()->id;
                    $category->course_id = $get('course_id');
                    $category->name = $data['category_name'];
                    $category->save();
                })
                ->hidden(fn (Get $get) => $get('course_id') === null || $get('course_id') === ''),
            RichEditor::make( 'frontside' )
            ->label( 'Vorderseite' )
            ->required()
            ->maxLength( 16777215 )
            ->columnSpanFull()
            ->fileAttachmentsDirectory( auth()->user()->id.'/flashcard_images' )
            ->fileAttachmentsVisibility( 'private' ),
            RichEditor::make( 'backside' )
            ->label( 'Rückseite' )
            ->required()
            ->maxLength( 16777215 )
            ->columnSpanFull()
            ->fileAttachmentsDirectory( auth()->user()->id.'/flashcard_images' )
            ->fileAttachmentsVisibility( 'private' ),
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->columns( [
            TextColumn::make( 'course.name' )
                ->label( 'Fach' )
                ->sortable()
                ->searchable(isIndividual: true),
            TextColumn::make( 'category.name' )
                ->label( 'Kategorie' )
                ->sortable()
                ->searchable(isIndividual: true),
            TextColumn::make( 'frontside' )
                ->label( 'Karteikarte' )
                ->limit( 100 )
                ->searchable()
                ->html(),
            IconColumn::make( 'deleted_at' )
                ->label( 'Status' )
                ->boolean()
                ->getStateUsing( fn ( $record ): bool => blank( $record->deleted_at ) )
                ->sortable(),
            TextColumn::make( 'created_at' )
                ->label( 'Erstellt am' )
                ->dateTime( 'd.m.Y H:i:s' )
                ->sortable()
                ->toggleable( isToggledHiddenByDefault: false ),
            TextColumn::make( 'updated_at' )
                ->label( 'Zuletzt geändert am' )
                ->dateTime( 'd.m.Y H:i:s' )
                ->sortable()
                ->toggleable( isToggledHiddenByDefault: false ),
        ] )
        ->defaultSort( 'created_at', 'desc' )
        ->filters( [
            Tables\Filters\TrashedFilter::make(),
        ] )
        ->actions( [
            Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\BulkActionGroup::make( [
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ] ),
        ] );
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListFlashcards::route( '/' ),
            'create' => Pages\CreateFlashcard::route( '/create' ),
            'edit' => Pages\EditFlashcard::route( '/{record}/edit' ),
        ];
    }

    public static function getEloquentQuery(): Builder {
        $user = auth()->user();
        return Flashcard::query()
        ->where( 'user_id', $user->id )
                ->withoutGlobalScopes( [
                    SoftDeletingScope::class,
                ] );
    }
}
