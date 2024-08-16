<?php

namespace App\Filament\Widgets;

use App\Models\Quiz;
use App\Models\QuizStatistic;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class QuizzesStats extends BaseWidget
{
    protected function getStats(): array
    {
        $authUserId = auth()->user()->id;
        $userStatisticCurrentMonth = QuizStatistic::where('user_id', $authUserId)
                                                ->whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->get();
        $userQuizzes = Quiz::where('user_id', $authUserId)->get();

        return [
            Stat::make('Anzahl Quizzes', $userQuizzes->count()),
            Stat::make('Anzahl vollständig durchgeführte Quizzes in diesem Monat', $userStatisticCurrentMonth->count()),
        ];
    }
}
