<div>
    @if(!$learningProcessStarted)
    <x-filament::fieldset>
        <div class="grid grid-cols-3 gap-4" >
            <div>
                <label for="">Quiz</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live='selectedQuizId'>
                        <option value="" selected>Wählen Sie eine Option</option>
                        @foreach ($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">{{ $quiz->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        <div class="py-4">
            @if ($selectedQuizId)
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
                            @if($showingQuestionResult)
                                <span>
                                    @if(in_array($answer->id, $correctlyAnsweredQuestions))
                                        <x-icon-correct/>
                                    @else
                                        <x-icon-incorrect/>
                                    @endif
                                </span>
                            @endif
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
            @if(!$showingQuestionResult)
                <x-filament::button color="primary" wire:click="showQuestionResult()">
                    Auflösen
                </x-filament::button>
            @elseif($questions->count() > 0)
                <x-filament::button color="primary" wire:click="finishQuestion()">
                    Nächste Frage
                </x-filament::button>
            @endif

            @if($showSummaryModalButton)
                <x-filament::button color="primary" wire:click="finishQuiz()">
                    Ergebnis anzeigen
                </x-filament::button>
            @endif
        </div>
    @endif

    <x-filament::modal id="summary">
        Gratuliere - du hast es geschafft.

        @if ($numberQuestionsSucceeded === 1)
            Du hast 1 Frage richtig beantwortet und {{ $numberQuestionsFailed }} nicht.
        @else
            Du hast {{ $numberQuestionsSucceeded }} Fragen korrekt beantwortet und {{ $numberQuestionsFailed }} nicht.
        @endif

        <x-filament::button color="gray" wire:click="redirectToLearnQuizzesEntryPoint">
            Zurück zur Übersicht
        </x-filament::button>

    </x-filament::modal>
</div>
