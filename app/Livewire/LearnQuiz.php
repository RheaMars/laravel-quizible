<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LearnQuiz extends Component
{
    public Collection $quizzes;
    public ?int $selectedQuizId;

    public Quiz $quiz;

    public ?Collection $questions;

    public bool $learningProcessStarted = false;

    public int $numberOfQuestionsToLearn;

    public Question $currentLearnedQuestion;

    public array $checkedAnswers = [];

    public function mount()
    {
        $user = Auth::user();
        $this->quizzes = $user->quizzes->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        $this->selectedQuizId = null;
        $this->questions = null;
    }

    public function render()
    {
        return view('livewire.learn-quiz');
    }

    public function learnQuiz(): void {
        $this->learningProcessStarted = true;

        $this->quiz = Quiz::findOrFail($this->selectedQuizId);

        $this->questions = $this->quiz->questions->sortBy('sort');

        $this->numberOfQuestionsToLearn = $this->questions->count();
        $this->currentLearnedQuestion = $this->questions->shift();
    }

    public function showQuestionResult(): void {
        dd($this->checkedAnswers);

        //TODO Read checked answers and compute result
    }
}
