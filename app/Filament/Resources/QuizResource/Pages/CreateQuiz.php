<?php

namespace App\Filament\Resources\QuizResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\QuizResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuiz extends CreateRecord {
    protected static string $resource = QuizResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate( array $data ): array {
        $data[ 'creator_id' ] = Auth::user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string {
        return $this->previousUrl ?? $this->getResource()::getUrl( 'index' );
    }
}
