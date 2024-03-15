<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use App\Models\Category;
use App\Models\FlashCard;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LearnFlashCards extends Component
{
    use WithPagination;

    public $courses;
    public $categories;
    public $selectedCourseId;
    public $selectedCategoryId;
    public $flashcards;

    public bool $learningProcessStarted = false;

    public FlashCard $currentLearnedFlashcard;

    public int $numberOfFlashcardsInCourse;

    public bool $showFlashcardsBackside = false;

    public string $shownSideOfCurrentFlashcard;

    public bool $learningCycleActive = false;

    public Collection $flashcardsSuccess;

    public Collection $flashcardsFail;

    public function mount() {
        $user = Auth::user();
        $courses = $user->courses->sortBy('name');
        $this->courses = $courses->filter(function ($course) {
            return $course->flashcards->count() > 0;
        });
        $this->categories = null;
        $this->selectedCourseId = null;
        $this->selectedCategoryId = null;
        $this->flashcards = null;
        $this->flashcardsSuccess = new Collection();
        $this->flashcardsFail = new Collection();
    }

    public function updatedSelectedCourseId($course) {
        $this->flashcards = null;
        $this->selectedCategoryId = null;
        if ($course === '') {
            $this->selectedCourseId = null;
            $this->categories = null;
            $this->numberOfFlashcardsInCourse = 0;
        } else {
            $categories = Category::where('course_id', $course)->get()->sortBy('name');
            $this->categories = $categories->filter(function ($category) {
               return $category->flashcards->count() > 0;
            });
            $selectedCourse = Course::findOrFail($this->selectedCourseId);
            $this->numberOfFlashcardsInCourse = $selectedCourse->flashcards->count();
        }
    }

    public function learnFlashCards() {

        $this->learningProcessStarted = true;
        $this->learningCycleActive = true;

        if($this->selectedCategoryId) {
            $this->flashcards = FlashCard::where('category_id', $this->selectedCategoryId)->inRandomOrder()->get();
        } else {
            $this->flashcards = FlashCard::where('course_id', $this->selectedCourseId)->inRandomOrder()->get();
        }

        $this->currentLearnedFlashcard = $this->flashcards->shift();
        $this->setSideOfCurrentFlashcardToShow();
    }

    public function finishFlashCard(bool $flashCardKnown) {
        if($flashCardKnown) {
            $this->flashcardsSuccess->push($this->currentLearnedFlashcard);
        } else {
            $this->flashcardsFail->push($this->currentLearnedFlashcard);
        }
        if($this->flashcards->count() != 0) {
            $this->currentLearnedFlashcard =  $this->flashcards->shift();
            $this->setSideOfCurrentFlashcardToShow();
        } else {
            $this->learningCycleActive = false;
            $this->dispatch('open-modal', id: 'summary');
        }
    }

    public function turnAroundFlashCard() {
        $this->toggleSideOfCurrentFlashcardToShow();
    }

    public function render()
    {
        return view('livewire.learn-flash-cards');
    }

    private function setSideOfCurrentFlashcardToShow()
    {
        $this->shownSideOfCurrentFlashcard = $this->currentLearnedFlashcard->frontside;
        if ($this->showFlashcardsBackside) {
            $this->shownSideOfCurrentFlashcard = $this->currentLearnedFlashcard->backside;
        }
    }

    private function toggleSideOfCurrentFlashcardToShow()
    {
        if ($this->shownSideOfCurrentFlashcard === $this->currentLearnedFlashcard->frontside) {
            $this->shownSideOfCurrentFlashcard = $this->currentLearnedFlashcard->backside;
        }
        else  {
            $this->shownSideOfCurrentFlashcard = $this->currentLearnedFlashcard->frontside;
        }
    }
}
