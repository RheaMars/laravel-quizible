<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use App\Models\Invitation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvitationResource\Pages;
use App\Filament\Resources\InvitationResource\RelationManagers;
use Illuminate\Support\Str;

class InvitationResource extends Resource
{
    protected static ?string $navigationGroup = 'Admin-Bereich';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Invitation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Offene Einladungen';
    protected static ?string $title = 'Offene Einladungen';
    protected static ?string $pluralModelLabel = 'Offene Einladungen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('E-Mail')
                    ->email()
                    ->unique('invitations', 'email')
                    ->unique('users', 'email')
                    ->required(),
                Select::make('roles')->label('Rollen')
                    ->required()
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->label('E-Mail')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Eingeladen am')->dateTime('d.m.Y H:i:s')->sortable(),
                TextColumn::make('token')->label('Token')
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInvitations::route('/'),
            'create' => Pages\CreateInvitation::route('/create'),
        ];
    }
}
