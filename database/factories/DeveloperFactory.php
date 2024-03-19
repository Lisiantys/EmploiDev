<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Location;
use App\Models\Developer;
use App\Models\TypesContract;
use App\Models\TypesDeveloper;
use App\Models\YearsExperience;
use App\Models\ProgrammingLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profil_image' => fake()->imageUrl(640, 480, 'persone'),
            'first_name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'cv' => fake()->url(),
            'cover_letter' => fake()->url(),
            'is_free' => fake()->boolean(),
            'is_validated' => fake()->boolean(),
            //Pluck récupére une collection contenant les valeurs d'une colonne spécifique.
            'contract_id' => TypesContract::pluck('id')->random(),
            'year_id' => YearsExperience::pluck('id')->random(),
            'location_id' => Location::pluck('id')->random(),
            'type_id' => TypesDeveloper::pluck('id')->random(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Developer $developer) {
            // Cela crée un utilisateur mais ne le sauvegarde pas encore dans la base de données.
            $user = User::factory()->create([
                'role_id' => 1,
            ]);
            $developer->user_id = $user->id;;
        })->afterCreating(function (Developer $developer) {
            $languageIds = ProgrammingLanguage::pluck('id');
            $developer->programmingLanguages()->attach($languageIds->random(rand(1, 5)));
            // Assure que le User associé est maintenant en BDD.
            $developer->user->save();
        });
    }
}
