<?php

namespace Database\Seeders;

use App\Models\TypesDeveloper;
use Illuminate\Database\Seeder;

class TypesDevelopersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Frontend', 'Backend', 'Full Stack', 'Mobile', 'Data Scientist'];

        foreach ($types as $type) {
            TypesDeveloper::create(['name' => $type]);
        }
    }
}
