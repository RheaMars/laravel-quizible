<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\FlashCard;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\FlashCardResource;


class LearnFlashCards extends Component
{
    public $courses;
    public $categories;
    public $selectedCourse;
    public $selectedCategory;
    public $flashcards;

    public function mount() {
        $user = Auth::user();
        $this->courses = $user->courses->all();
        $this->categories = null;
        $this->selectedCourse = null;
        $this->selectedCategory = null;
        $this->flashcards = null;
    }

    public function updatedSelectedCourse($course) {
        if ($course === '') {
            $this->selectedCourse = null;
            $this->categories = null;
        } else {
            $this->categories = Category::where('course_id', $course)->get()->sortBy('name');
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
