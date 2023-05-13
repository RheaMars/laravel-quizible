<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ])->assignRole(Role::create(['name' => 'admin']));

        User::factory()->create([
            'name' => 'Teacher',
            'email' => 'teacher@teacher.com',
        ])->assignRole(Role::create(['name' => 'teacher']));

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
        ])->assignRole(Role::create(['name' => 'user']));
    }
}
