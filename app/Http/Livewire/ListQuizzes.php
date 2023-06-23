<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListQuizzes extends Component implements HasTable {

    use InteractsWithTable;

    protected function getTableQuery(): Builder {
        $user = auth()->user();
        return Quiz::query()
        ->where( 'creator_id', $user->id )
        ->withTrashed();
    }

    protected function getDefaultTableSortColumn(): ?string {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string {
        return 'asc';
    }

    protected function getTableColumns(): array {
        return [
            TextColumn::make( 'name' )->label( 'Name' )->sortable()->searchable(),
            TextColumn::make( 'questions_count' )->label( 'Fragen' )->counts( 'questions' )->sortable(),
            TextColumn::make( 'created_at' )->label( 'Erstellt am' )
            ->dateTime( 'd.m.Y H:i:s' )
            ->sortable()
            ->searchable(),
            TextColumn::make( 'updated_at' )->label( 'Zuletzt geändert am' )
            ->dateTime( 'd.m.Y H:i:s' )
            ->sortable()
            ->searchable(),
        ];
    }

    public function render() {
        return view( 'livewire.list-quizzes' );
    }

    protected function getTableBulkActions(): array {
        return [
            DeleteBulkAction::make()->modalHeading( 'Ausgewählte Quizzes löschen' ),
            ForceDeleteBulkAction::make()->modalHeading( 'Ausgewählte Quizzes endgültig löschen' ),
            RestoreBulkAction::make()->modalHeading( 'Ausgewählte Quizzes wiederherstellen' ),
        ];
    }

    protected function getTableFilters(): array {
        return [
            TrashedFilter::make(),
        ];
    }

}

