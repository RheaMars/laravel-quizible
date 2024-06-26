<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LearnFlashcards extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static string $view = 'filament.pages.learn-flashcards';

    protected static ?string $title = 'Karteikarten lernen';

    protected static ?string $navigationGroup = 'Karteikarten';

    protected static ?string $navigationLabel = 'Karteikarten lernen';

    protected static ?int $navigationSort = 2;
}
