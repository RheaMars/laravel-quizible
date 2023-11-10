<?php

namespace App\Filament\Resources\FlashCardResource\Pages;

use App\Filament\Resources\FlashCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFlashCard extends EditRecord {
    protected static string $resource = FlashCardResource::class;
    protected static ?string $title = 'Karteikarte bearbeiten';

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string {
        return $this->previousUrl ?? $this->getResource()::getUrl( 'index' );
    }
}
