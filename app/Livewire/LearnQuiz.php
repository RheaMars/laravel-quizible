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

    public array $correctlyAnsweredQuestions = [];

    public array $incorrectlyAnsweredQuestions = [];

    public bool $showingQuestionResult = false;

    public int $numberQuestionsSucceeded = 0;

    public int $numberQuestionsFailed = 0;

    public bool $showSummaryModalButton = false;

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

        $this->showingQuestionResult = true;
        if ($this->questions->count() === 0) {
            $this->showSummaryModalButton = true;
        }

        $atLeastOneError = false;
        foreach($this->currentLearnedQuestion->answers()->get() as $answer) {
            // Check if user checked a correct answer:
            if ($answer->is_correct) {
                if (array_key_exists($answer->id, $this->checkedAnswers) && true === $this->checkedAnswers[$answer->id]) {
                    $this->correctlyAnsweredQuestions[] = $answer->id;
                }
                else {
                    $this->incorrectlyAnsweredQuestions[] = $answer->id;
                    $atLeastOneError = true;
                }
            }
            // Check if user did not check an incorrect answer:
            else {
                if (!array_key_exists($answer->id, $this->checkedAnswers) || false === $this->checkedAnswers[$answer->id]) {
                    $this->correctlyAnsweredQuestions[] = $answer->id;
                }
                else {
                    $this->incorrectlyAnsweredQuestions[] = $answer->id;
                    $atLeastOneError = true;
                }
            }
        }

        if ($atLeastOneError) {
            $this->numberQuestionsFailed++;
        } else {
            $this->numberQuestionsSucceeded++;
        }
    }

    public function finishQuestion(): void
    {
        $this->showingQuestionResult = false;
        $this->correctlyAnsweredQuestions = [];
        $this->incorrectlyAnsweredQuestions = [];

        if ($this->questions->count() > 0) {
            $this->currentLearnedQuestion =  $this->questions->shift();
        }
    }

    public function finishQuiz(): void
    {
        $this->dispatch('open-modal', id: 'summary');
    }

    public function redirectToLearnQuizzesEntryPoint(): void
    {
        $this->redirect('/learn-quiz');
    }
}
