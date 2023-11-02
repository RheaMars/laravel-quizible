<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Benutzer bearbeiten';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->recordTitle('Benutzer'),
            ForceDeleteAction::make()->recordTitle('Benutzer'),
            RestoreAction::make()->recordTitle('Benutzer'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
