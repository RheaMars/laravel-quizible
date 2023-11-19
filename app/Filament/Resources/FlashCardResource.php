<?php

namespace App\Filament\Resources;

use App\Models\Course;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\FlashCard;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FlashCardResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FlashCardResource extends Resource {
    protected static ?string $model = FlashCard::class;

    protected static ?string $navigationIcon = 'bi-card-text';
    protected static ?string $navigationLabel = 'Karteikarten';
    protected static ?string $title = 'Karteikarte';
    protected static ?string $pluralModelLabel = 'Karteikarten';
    protected static ?string $modelLabel = 'Karteikarte';

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            Select::make('course')->label('Fach')
                ->options(Auth::user()->courses()->pluck('name', 'id')->sort())
                ->hintAction(
                    Action::make('addCourse')
                        ->label('Fach erstellen')
                        ->form([
                            TextInput::make('course_name')->label('Name')->required()
                        ])
                        ->action(function (array $data, Course $course): void {
                            $course->user_id = Auth::user()->id;
                            $course->name = $data['course_name'];
                            $course->save();
                        }),
                ),
            //TODO Replace by Select (dynamic text input is not working correctly)
            TextInput::make( 'category' )->label( 'Kategorie' )
                ->live()
                ->datalist( function ( ?string $state, TextInput $component, $modelsearch = '\App\Models\FlashCard', $fieldsearch = 'category' ) {
                    if($state == null) {
                        $options = $modelsearch::whereRaw('user_id = ' . auth()->user()->id);
                    } else {
                        $options = $modelsearch::whereRaw($fieldsearch.' like \'%'.$state.'%\''.' and user_id = ' . auth()->user()->id);
                    }
                    return $options
                        ->distinct()
                        ->limit(20)
                        ->pluck('category')
                        ->toArray();
            }),
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
                ->searchable(),
            TextColumn::make( 'category' )
                ->label( 'Kategorie' )
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListFlashCards::route( '/' ),
            'create' => Pages\CreateFlashCard::route( '/create' ),
            'edit' => Pages\EditFlashCard::route( '/ {record}/edit' ),
        ];
    }

    public static function getEloquentQuery(): Builder {
        $user = auth()->user();
        return FlashCard::query()
        ->where( 'user_id', $user->id )
                ->withoutGlobalScopes( [
                    SoftDeletingScope::class,
                ] );
            }
        }
