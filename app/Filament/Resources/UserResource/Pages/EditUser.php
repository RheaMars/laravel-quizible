<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Benutzer bearbeiten';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->recordTitle('Benutzer'),
            Actions\ForceDeleteAction::make()->recordTitle('Benutzer'),
            Actions\RestoreAction::make()->recordTitle('Benutzer'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
