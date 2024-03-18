<?php

namespace Database\Seeders;

use App\Models\TypesContract;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypesContractsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = ['CDI', 'CDD', 'Stage', 'Alternance'];

        foreach ($contracts as $contract) {
            TypesContract::create(['name' => $contract]);
        }
    }
}
