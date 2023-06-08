<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class ListQuizzes extends Component implements HasTable
{
    use InteractsWithTable;


    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        return Quiz::query()
            ->where('creator_id', $user->id);
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return "created_at";
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return "asc";
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Name')->sortable()->searchable(),
            TextColumn::make('created_at')->label('Erstellt am')
                ->dateTime('d.m.Y H:i:s')
                ->sortable()
                ->searchable(),
        ];
    }

    public function render()
    {
        return view('livewire.list-quizzes');
    }
}
