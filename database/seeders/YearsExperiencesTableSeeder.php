<?php

namespace Database\Seeders;

use App\Models\YearsExperience;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class YearsExperiencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = ['Moins d\'un an', '1-2 ans', '3-5 ans', 'Plus de 5 ans'];

        foreach ($years as $year) {
            YearsExperience::create(['name' => $year]);
        }
    }
}
