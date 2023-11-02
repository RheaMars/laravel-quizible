<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = 'Admin-Bereich';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Benutzer';
    protected static ?string $title = 'Benutzer';
    protected static ?string $pluralModelLabel = 'Benutzer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Name')
                    ->required()
                    ->maxLength(200),
                TextInput::make('email')->label('E-Mail')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->email(),
                Select::make('roles')->label('Rollen')
                    ->multiple()
                    ->required()
                    ->relationship('roles', 'name')
                    ->preload(),
                TextInput::make('password')->label('Passwort')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'), // only required in create context
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')->label('E-Mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')->label('Rollen')
                    ->searchable(),
                TextColumn::make('created_at')->label('Erstellt am')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('deleted_at')
                    ->label('Status')
                    ->options([
                        'heroicon-o-check-badge',
                        'heroicon-o-trash' => fn ($state, $record): bool => $record->deleted_at != null
                    ])
                    ->colors([
                        'secondary',
                        'danger' => fn ($state, $record): bool => $record->deleted_at != null,
                    ])
                    ->sortable()
            ])
            ->defaultSort('name')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
