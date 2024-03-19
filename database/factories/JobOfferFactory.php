<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\Location;
use App\Models\TypesContract;
use App\Models\TypesDeveloper;
use App\Models\YearsExperience;
use App\Models\ProgrammingLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'is_validated' => fake()->boolean(),
            'contract_id' => TypesContract::pluck('id')->random(),
            'year_id' => YearsExperience::pluck('id')->random(),
            'location_id' => Location::pluck('id')->random(),
            'type_id' => TypesDeveloper::pluck('id')->random(),
            'company_id' => Company::pluck('id')->random(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (JobOffer $jobOffer) {
            $languageIds = ProgrammingLanguage::pluck('id');
            $jobOffer->programmingLanguages()->attach($languageIds->random(rand(1, 5)));
        });
    }
}
