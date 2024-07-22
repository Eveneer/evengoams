<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class DonorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $is_individual = rand(0, 9) < 7;

        $donor = [
            'name' => $is_individual ? fake()->name() : fake()->company,
            'is_individual' => $is_individual,
        ];
        
        if (rand(0, 9) >= 5)
            $donor['phone'] = fake()->phoneNumber();

        if (rand(0, 9) >= 5)
            $donor['email'] = fake()->unique()->safeEmail();

        if (rand(0, 9) >= 5)
            $donor['address'] = fake()->address();

        if ($is_individual) {
            $donor['details'] = [
                'company' => fake()->company(),
                'occupation' => fake()->jobTitle()
            ];
        } else {
            $donor['details'] = [];
        }
        

        return $donor;
    }
}
