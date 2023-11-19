<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\FlashCard;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(Course::factory()->count(5))
            ->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole(Role::create(['name' => 'admin']));

        User::factory()
            ->has(Course::factory()->count(10))
            ->create([
            'name' => 'Teacher',
            'email' => 'teacher@teacher.com',
        ])->assignRole(Role::create(['name' => 'teacher']));

        User::factory()
            ->has(Course::factory()->count(5))
            ->create([
            'name' => 'Student',
            'email' => 'student@student.com',
        ])->assignRole(Role::create(['name' => 'student']));

       foreach (User::all() as $user) {

           Quiz::factory(10)->create([
               'user_id' => $user->id,
           ]);

           for ($i = 1; $i <= 30; $i++) {
               FlashCard::factory()->create([
                   'user_id' => $user->id,
                   'course_id' => $user->courses->shuffle()->first()->id
               ]);
           }
       }

       foreach (Quiz::all() as $quiz) {
           Question::factory(5)->create([
               'quiz_id' => $quiz->id,
           ]);
       }

       foreach (Question::all() as $question) {
           $numberOfAnswers = 3;
           if ($question->type === 'true-false') {
               $numberOfAnswers = 1;
           }
           Answer::factory($numberOfAnswers)->create([
               'question_id' => $question->id,
           ]);
       }
    }
}
