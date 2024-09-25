<?php

namespace Database\Factories;

use App\Domains\Vendors\Enums\VendorTypesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $industries = ['Cleaning', 'Carpets', 'Garments', 'Electrical Appliance', 'Books', 'Furnishing'];
        $tags = [];

        while (rand(0, 9) < 7 - count($tags)) {
            $el = fake()->randomElement($industries);

            if (!in_array($el, $tags))
                $tags[] = $el;
        }

        $contacts = [];

        while (rand(0, 9) < 5 - count($contacts)) {
            $rand = rand(0, 9);
            $contact = ['name' => fake()->name()];

            if ($rand <= 3 || $rand >= 7)
                $contact['phone'] = fake()->phoneNumber();

            if ($rand >= 4)
                $contact['email'] = fake()->unique()->safeEmail();

            $contacts[] = $contact;
        }

        return [
            'name' => fake()->company(),
            'type' => fake()->randomElement(VendorTypesEnum::getValues()),
            'tags' => $tags,
            'contacts' => $contacts
        ];
    }
}
