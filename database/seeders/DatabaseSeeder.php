<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Developer;
use App\Models\Application;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            LocationsTableSeeder::class,
            YearsExperiencesTableSeeder::class,
            ProgrammingLanguagesTableSeeder::class,
            TypesDevelopersTableSeeder::class,
            TypesContractsTableSeeder::class,
            UsersTableSeeder::class,
        ]);

        // Créez des Developers et Companies, chaque création entraînera aussi la création d'un User associé avec le bon role_id.
        Developer::factory(10)->create();
        Company::factory(5)->create();

        // Pour chaque Company créée, créez des offres d'emplois entre 1 et 5.
        Company::all()->each(function ($company) {
            JobOffer::factory(rand(1, 5))->create(['company_id' => $company->id]);
        });

        // Pour chaque offre d'emplois créée, créez des candidatures(application) entre 1 et 10 .
        JobOffer::all()->each(function ($jobOffer) {
        Application::factory(rand(1, 10))->create(['job_id' => $jobOffer->id]);
        });
    }
}
