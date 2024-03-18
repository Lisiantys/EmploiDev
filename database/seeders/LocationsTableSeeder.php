<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['city' => 'New York', 'postal_code' => '10001'],
            ['city' => 'Los Angeles', 'postal_code' => '90001'],
            // Ajoutez autant de locations que nécessaire
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
