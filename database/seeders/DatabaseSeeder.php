<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Flashcard;
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
            ->has(Course::factory()->count(5))
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

        foreach (Course::all() as $course) {
            Category::factory(3)->create([
                'course_id' => $course->id,
                'user_id' => $course->user_id,
            ]);
        }

        foreach (User::all() as $user) {

            Quiz::factory(10)->create([
                'user_id' => $user->id,
            ]);

            for ($i = 1; $i <= 30; $i++) {

                $course = $user->courses->shuffle()->first();

                Flashcard::factory()->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'category_id' => $course->categories->shuffle()->first()->id,
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
                Answer::factory($numberOfAnswers)->create([
                    'question_id' => $question->id,
                    'content' => null,
                ]);
            }
            else {
                Answer::factory($numberOfAnswers)->create([
                    'question_id' => $question->id,
                ]);
            }

        }
    }
}
