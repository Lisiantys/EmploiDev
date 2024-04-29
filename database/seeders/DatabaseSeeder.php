<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
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
            UsersTableSeeder::class
        ]);

        // Créez des Developers et Companies, chaque création entraînera aussi la création d'un User associé avec le bon role_id.
        Developer::factory(10)->create();
        Company::factory(5)->create();

        // Pour chaque Company créée, créez des JobOffers associées.
        Company::all()->each(function ($company) {
            JobOffer::factory(rand(1, 5))->create(['company_id' => $company->id]);
        });

        // Pour chaque JobOffer créée, créez des candidatures(application).
        // Cela crée des candidatures pour les offres d'emploi.
        JobOffer::all()->each(function ($jobOffer) {
        Application::factory(rand(1, 10))->create(['job_id' => $jobOffer->id]);
        });
    }
}
