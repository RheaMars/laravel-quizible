<?php

namespace Database\Seeders;

use App\Models\FlashCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class FlashCardSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('flash_cards')->truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path('database/data/flashcards.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ';')) !== FALSE) {
            if (!$firstline) {
                FlashCard::create([
                    'frontside' => $data['0'],
                    'backside' => $data['1'],
                    'user_id' => $data['2'],
                    'course_id' => $data['3'],
                    'category_id' => $data['4'],
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
