<div>
    @if(!$learningProcessStarted)
    <x-filament::fieldset>
        <div class="grid grid-cols-3 gap-4" >
            <div>
                <label for="">Quiz</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model='selectedQuizId'>
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
    @endif

    @if($learningProcessStarted)
        <div class="font-bold py-4">{{ $quiz->name }}</div>
        <div>noch {{ $questions->count() + 1}} von {{ $numberOfQuestionsToLearn }} Fragen</div>
        <x-filament::fieldset>
            <div>{{ $currentLearnedQuestion->content }}</div>
        </x-filament::fieldset>
        <x-filament::fieldset>
            @if($currentLearnedQuestion->type === "multiple-choice")
                @foreach($currentLearnedQuestion->answers->sortBy("sort") as $answer)
                    <div>
                        <label>
                            <x-filament::input.checkbox wire:model="checkedAnswers.{{ $answer->id }}"/>
                            <span>{{$answer->content}}</span>
                        </label>
                    </div>
                @endforeach
            @elseif($currentLearnedQuestion->type === "true-false")
                TODO Wahr-Falsch-Frage
            @endif
        </x-filament::fieldset>
        <div class="gap-3 flex flex-wrap items-center justify-start py-4">
            <x-filament::button color="primary" wire:click="showQuestionResult()">
                Auflösen
            </x-filament::button>
        </div>
    @endif
</div>
