<?php

namespace Database\Factories;

use App\Models\JobOffer;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->paragraph(),
            'job_id' => JobOffer::pluck('id')->random(),
            'developer_id' => Developer::pluck('id')->random(),
            'cv' => 'storage/cv.pdf',
            'cover_letter' => 'storage/cover_letter.pdf',
            'status' => fake()->randomElement(['accepted', 'rejected', 'pending']),
        ];
    }
}
