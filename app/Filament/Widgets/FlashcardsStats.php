<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Flashcard;
use App\Models\FlashcardStatistic;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class FlashcardsStats extends BaseWidget
{
    protected function getStats(): array
    {
        $authUserId = auth()->user()->id;
        $userStatisticCurrentMonth = FlashcardStatistic::where('user_id', $authUserId)
                                                ->whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->get();
        $userStatisticCurrentMonthKnown = FlashcardStatistic::where('user_id', $authUserId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('known', true)
            ->get();
        $userStatisticCurrentMonthUnknown = FlashcardStatistic::where('user_id', $authUserId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('known', false)
            ->get();
        $userFlashcards = Flashcard::where('user_id', $authUserId)->get();

        return [
            Stat::make('Anzahl Karteikarten', $userFlashcards->count()),
            Stat::make('Anzahl gelernte Karteikarten in diesem Monat', $userStatisticCurrentMonth->count()),
            Stat::make('Anzahl gewusste Karteikarten in diesem Monat', $userStatisticCurrentMonthKnown->count()),
            Stat::make('Anzahl nicht gewusste Karteikarten in diesem Monat', $userStatisticCurrentMonthUnknown->count()),
        ];
    }
}
