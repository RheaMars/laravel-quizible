<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Category;
use App\Models\Question;
use App\Models\FlashCard;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\FlashCardSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()
            //->has(Course::factory()->count(5))
            ->create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
            ])->assignRole(Role::create(['name' => 'admin']));

        // Teacher
        User::factory()
            //->has(Course::factory()->count(5))
            ->create([
                'name' => 'Teacher',
                'email' => 'teacher@teacher.com',
            ])->assignRole(Role::create(['name' => 'teacher']));

        // Student
        User::factory()
            //->has(Course::factory()->count(5))
            ->create([
                'name' => 'Student',
                'email' => 'student@student.com',
            ])->assignRole(Role::create(['name' => 'student']));

        // Englisch-Course
        Course::create([
            'name' => 'Englisch',
            'user_id' => 1
        ]);

        // Categories for Englisch-Course
        Category::create([
            'name' => 'Nomen',
            'user_id' => 1,
            'course_id' => 1
        ]);

        Category::create([
            'name' => 'Verben',
            'user_id' => 1,
            'course_id' => 1
        ]);

        Category::create([
            'name' => 'Adjektive',
            'user_id' => 1,
            'course_id' => 1
        ]);

        $this->call([
            FlashCardSeeder::class,
        ]);

        /* foreach (Course::all() as $course) {
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

                FlashCard::factory()->create([
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
                $numberOfAnswers = 1;
            }
            Answer::factory($numberOfAnswers)->create([
                'question_id' => $question->id,
            ]);
        } */


    }
}
