<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['city' => 'Bordeaux', 'postal_code' => '33000'],
            ['city' => 'Toulouse', 'postal_code' => '31000'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
