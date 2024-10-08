<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\FlashcardStatistic;
use Livewire\Component;
use App\Models\Category;
use App\Models\Flashcard;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LearnFlashcards extends Component
{
    use WithPagination;

    public $courses;
    public $categories;
    public $selectedCourseId;
    public $selectedCategoryId;
    public $flashcards;

    public bool $learningProcessStarted = false;

    public Flashcard $currentLearnedFlashcard;

    public int $numberOfFlashcardsInCourse;

    public bool $showFlashcardsBackside = false;

    public string $shownSideOfCurrentFlashcard;

    public bool $learningCycleActive = false;

    public Collection $flashcardsSuccess;

    public Collection $flashcardsFail;

    public int $numberOfFlashcardsToLearn;

    public function mount() {
        $user = Auth::user();
        $courses = $user->courses->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
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

    public function learnFlashcards(bool $isRelearnFlashcards = false) {

        $this->learningProcessStarted = true;
        $this->learningCycleActive = true;

        if (!$isRelearnFlashcards) {
            if($this->selectedCategoryId) {
                $this->flashcards = Flashcard::where('category_id', $this->selectedCategoryId)->inRandomOrder()->get();
            } else {
                $this->flashcards = Flashcard::where('course_id', $this->selectedCourseId)->inRandomOrder()->get();
            }
        }

        $this->numberOfFlashcardsToLearn = $this->flashcards->count();
        $this->currentLearnedFlashcard = $this->flashcards->shift();
        $this->setSideOfCurrentFlashcardToShow();
    }

    public function finishFlashcard(bool $flashcardKnown) {
        if($flashcardKnown) {
            $this->flashcardsSuccess->push($this->currentLearnedFlashcard);
        } else {
            $this->flashcardsFail->push($this->currentLearnedFlashcard);
        }

        // Write statistics entry:
        FlashcardStatistic::create([
            'user_id' => $this->currentLearnedFlashcard->user->id,
            'flashcard_id' => $this->currentLearnedFlashcard->id,
            'known' => $flashcardKnown
        ]);

        if($this->flashcards->count() != 0) {
            $this->currentLearnedFlashcard =  $this->flashcards->shift();
            $this->setSideOfCurrentFlashcardToShow();
        } else {
            $this->learningCycleActive = false;
            $this->dispatch('open-modal', id: 'summary');
        }
    }

    public function turnAroundFlashcard() {
        $this->toggleSideOfCurrentFlashcardToShow();
    }

    public function redirectToLearnFlashcardsEntryPoint() {
        $this->redirect('/learn-flashcards');
    }

    public function relearnUnknownFlashcards() {
        $this->flashcards = $this->flashcardsFail;
        $this->flashcardsSuccess = new Collection();
        $this->flashcardsFail = new Collection();

        $this->dispatch('close-modal', id: 'summary');
        $this->learnFlashcards(true);
    }

    public function render()
    {
        return view('livewire.learn-flashcards');
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
