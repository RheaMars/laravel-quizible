<?php

namespace App\Filament\Resources\FlashCardResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\FlashCardResource;

class CreateFlashCard extends CreateRecord {
    protected static string $resource = FlashCardResource::class;

    protected static ?string $title = 'Karteikarte erstellen';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate( array $data ): array {
        $data[ 'user_id' ] = Auth::user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string {
        return $this->previousUrl ?? $this->getResource()::getUrl( 'index' );
    }

}
