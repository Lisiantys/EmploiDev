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
            ['city' => 'Paris', 'postal_code' => '75000'],
            ['city' => 'Marseille', 'postal_code' => '13000'],
            ['city' => 'Lyon', 'postal_code' => '69000'],
            ['city' => 'Toulouse', 'postal_code' => '31000'],
            ['city' => 'Nice', 'postal_code' => '06000'],
            ['city' => 'Nantes', 'postal_code' => '44000'],
            ['city' => 'Strasbourg', 'postal_code' => '67000'],
            ['city' => 'Montpellier', 'postal_code' => '34000'],
            ['city' => 'Bordeaux', 'postal_code' => '33000'],
            ['city' => 'Lille', 'postal_code' => '59000']
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
