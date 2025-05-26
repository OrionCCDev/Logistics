<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country; // Assuming you have a Country model for country_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Branch',
            'code' => $this->faker->unique()->bothify('BR-####??'),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'is_active' => $this->faker->boolean(90), // 90% chance of being true
            // 'country_id' is added by a different migration.
            // If you have a CountryFactory and want to create one:
            // 'country_id' => Country::factory(),
            // Or pick a random existing one, assuming they are seeded before branches:
            // 'country_id' => Country::inRandomOrder()->first()->id ?? Country::factory()->create()->id,
        ];
    }
}
