<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use App\Models\Category;
use App\Models\FlashCard;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\FlashCardResource;
use Livewire\WithPagination;

class LearnFlashCards extends Component
{
    use WithPagination;

    public $courses;
    public $categories;
    public $selectedCourseId;
    public $selectedCategoryId;
    public $flashcards;

    public $numberOfFlashcardsInCourse;

    public function mount() {
        $user = Auth::user();
        $this->courses = $user->courses->sortBy('name')->all();
        $this->categories = null;
        $this->selectedCourseId = null;
        $this->selectedCategoryId = null;
        $this->flashcards = null;
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
        if($this->selectedCategoryId) {
            $this->flashcards = FlashCard::where('category_id', $this->selectedCategoryId)->get();
        } else {
            $this->flashcards = FlashCard::where('course_id', $this->selectedCourseId)->get();
        }
    }

    public function render()
    {
        return view('livewire.learn-flash-cards');
    }
}
