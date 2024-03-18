<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgrammingLanguage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgrammingLanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = ['PHP', 'JavaScript', 'Python', 'Ruby', 'Java'];

        foreach ($languages as $language) {
            ProgrammingLanguage::create(['name' => $language]);
        }
    }
}
