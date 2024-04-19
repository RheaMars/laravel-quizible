<?php

namespace App\Filament\Resources\FlashcardResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\FlashcardResource;

class CreateFlashcard extends CreateRecord {
    protected static string $resource = FlashcardResource::class;

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
