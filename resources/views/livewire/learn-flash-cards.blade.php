<div>
    @if(!$learningProcessStarted)
    <x-filament::fieldset>
        <div class="grid grid-cols-3 gap-4" >
            <div>
                <label for="">Fach</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live='selectedCourseId'>
                        <option value="" selected>Wählen Sie eine Option</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <div>
                @if ($categories != null)
                    <label for="">Kategorie</label>
                    <x-filament::input.wrapper>
                        <x-filament::input.select wire:model.live='selectedCategoryId'>
                            <option value="" selected>Alle Kategorien (komplettes Fach) ({{$numberOfFlashcardsInCourse}})</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{$category->flashcards->count()}})</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                @endif
            </div>
        </div>
        @if ($categories != null)
            <div class="py-4">
                <label>
                    <x-filament::input.checkbox wire:model.live="showFlashcardsBackside" />
                    <span>von der Rückseite der Karteikarten lernen</span>
                </label>
            </div>
        @endif
        <div class="py-4">
            @if ($categories)
                <x-filament::button wire:click='learnFlashCards'>
                    Jetzt lernen
                </x-filament::button>
            @endif
        </div>
    </x-filament::fieldset>
    @endif

    @if($learningProcessStarted)
        <div class="font-bold py-4">{{$currentLearnedFlashcard->course->name}} {{$currentLearnedFlashcard->category->name}}</div>
        <x-filament::fieldset>
            <div>
                {!! $shownSideOfCurrentFlashcard !!}
            </div>
        </x-filament::fieldset>
        <div class="gap-3 flex flex-wrap items-center justify-start py-4">
            <x-filament::button wire:click='turnAroundFlashCard'>
                Karte umdrehen
            </x-filament::button>
            @if($learningCycleActive)
                <x-filament::button color="danger" wire:click="finishFlashCard(false)">
                        Nicht gewusst
                </x-filament::button>
                <x-filament::button color="success" wire:click="finishFlashCard(true)">
                        Gewusst
                </x-filament::button>
            @endif
        </div>
    @endif
    <x-filament::modal id="summary">
        Gratuliere - du hast es geschafft.
        @if ($flashcardsSuccess->count() === 1)
            Du hast 1 Antwort gewusst und {{ $flashcardsFail->count() }} nicht gewusst.
        @else
            Du hast {{ $flashcardsSuccess->count() }} Antworten gewusst und {{ $flashcardsFail->count() }} nicht gewusst.
        @endif
    </x-filament::modal>
</div>
