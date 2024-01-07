<?php

namespace App\Livewire;

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
    public $selectedCourse;
    public $selectedCategory;
    public $flashcards;

    public function mount() {
        $user = Auth::user();
        $this->courses = $user->courses->sortBy('name')->all();
        $this->categories = null;
        $this->selectedCourse = null;
        $this->selectedCategory = null;
        $this->flashcards = null;
    }

    public function updatedSelectedCourse($course) {
        $this->flashcards = null;
        $this->selectedCategory =  null;
        if ($course === '') {
            $this->selectedCourse = null;
            $this->categories = null;
        } else {
            $categories = Category::where('course_id', $course)->get()->sortBy('name');
            $this->categories = $categories->filter(function ($category) {
               return $category->flashcards->count() > 0;
            });
        }
    }

    public function learnFlashCards() {
        if($this->selectedCategory) {
            $this->flashcards = FlashCard::where('category_id', $this->selectedCategory)->get();
        } else {
            $this->flashcards = FlashCard::where('course_id', $this->selectedCourse)->get();
        }
    }

    public function render()
    {
        return view('livewire.learn-flash-cards');
    }
}
