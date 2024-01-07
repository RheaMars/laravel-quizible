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
        <label>
            <x-filament::input.checkbox wire:model.live="showFlashcardsBackside" />
            <span>Karteikarten umdrehen</span>
        </label>
        <div class="font-bold">{{$currentLearnedFlashcard->course->name}} {{$currentLearnedFlashcard->category->name}}</div>
        <x-filament::fieldset>
            <div>
                @if(!$showFlashcardsBackside)
                    {{$currentLearnedFlashcard->frontside}}
                @else
                    {{$currentLearnedFlashcard->backside}}
                @endif
            </div>
        </x-filament::fieldset>
        @if($flashcards->count() > 0)
            <x-filament::button wire:click='nextFlashCard'>
                    Weiter
            </x-filament::button>
        @endif
    @endif
</div>
