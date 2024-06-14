<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LearnQuiz extends Page
{
    protected static string $view = 'filament.pages.learn-quiz';

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $title = 'Quiz lernen';

    protected static ?string $navigationGroup = 'Quizzes';

    protected static ?string $navigationLabel = 'Quiz lernen';

    protected static ?int $navigationSort = 2;
}
