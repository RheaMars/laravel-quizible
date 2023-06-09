<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
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


        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole(Role::create(['name' => 'admin']));

        User::factory()->create([
            'name' => 'Teacher',
            'email' => 'teacher@teacher.com',
        ])->assignRole(Role::create(['name' => 'teacher']));

        User::factory()->create([
            'name' => 'Student',
            'email' => 'student@student.com',
        ])->assignRole(Role::create(['name' => 'student']));

        User::factory(10)->create();

        Quiz::factory()->count(30)->create();
        QuizQuestion::factory()->count(90)->create();
        QuizAnswer::factory()->count(270)->create();
    }
}
