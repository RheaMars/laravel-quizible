<?php

namespace App\Filament\Resources\InvitationResource\Pages;

use App\Filament\Resources\InvitationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvitations extends ListRecords
{
    protected static string $resource = InvitationResource::class;
    protected static ?string $title = 'Einladungen';

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Einladung versenden')->url(route('invite'))
        ];
    }

}
