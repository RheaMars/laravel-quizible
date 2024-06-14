<div>
    <x-filament::fieldset>
        <div class="grid grid-cols-3 gap-4" >
            <div>
                <label for="">Quiz</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select :searchable="true" wire:model='selectedQuizId'>
                        <option value="" selected>Wählen Sie eine Option</option>
                        @foreach ($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        <div class="py-4">
            @if ($quiz)
                <x-filament::button wire:click='learnQuiz'>
                    Jetzt lernen
                </x-filament::button>
            @endif
        </div>
    </x-filament::fieldset>


    {{-- @if($learningProcessStarted)
        <div class="font-bold py-4">{{$currentLearnedFlashcard->course->name}} {{$currentLearnedFlashcard->category->name}}</div>
        <div>noch {{ $flashcards->count() + 1 }} von {{ $numberOfFlashcardsToLearn }} Karteikarten zu lernen</div>
        <x-filament::fieldset>
            <div>
                {!! $shownSideOfCurrentFlashcard !!}
            </div>
        </x-filament::fieldset>
        <div class="gap-3 flex flex-wrap items-center justify-start py-4">
            <x-filament::button wire:click='turnAroundFlashcard'>
                Karte umdrehen
            </x-filament::button>
            @if($learningCycleActive)
                <x-filament::button color="danger" wire:click="finishFlashcard(false)">
                        Nicht gewusst
                </x-filament::button>
                <x-filament::button color="success" wire:click="finishFlashcard(true)">
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

        @if ($flashcardsFail->count() > 0)
            <x-filament::button color="primary" wire:click="relearnUnknownFlashcards">
                Nicht-gewusste Karten nochmals lernen
            </x-filament::button>
        @endif

        <x-filament::button color="gray" wire:click="redirectToLearnFlashcardsEntryPoint">
            Zurück zur Übersicht
        </x-filament::button>

    </x-filament::modal> --}}
</div>
