<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LearnQuiz extends Component
{

    public $quizzes;
    public $selectedQuizId;

    public function mount()
    {
        $user = Auth::user();
        $this->quizzes = $user->quizzes->sortBy('name');
        $this->selectedQuizId = null;

    }

    public function render()
    {
        return view('livewire.learn-quiz');
    }

    public function learnQuiz(): void {

    }
}
