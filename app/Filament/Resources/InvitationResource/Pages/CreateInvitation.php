<?php

namespace App\Filament\Resources\InvitationResource\Pages;

use App\Filament\Resources\InvitationResource;
use App\Notifications\InviteNotification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CreateInvitation extends CreateRecord
{
    protected static string $resource = InvitationResource::class;

    protected static ?string $title = 'Einladung versenden';
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['token'] = Str::random(20) . time();
        return $data;
    }

    protected function afterCreate() : void
    {
        $url = URL::temporarySignedRoute(
            'register', now()->addDays(7), ['token' => $this->record->token]
        );
        Notification::route('mail', $this->record->email)->notify(new InviteNotification($url));
    }

}
