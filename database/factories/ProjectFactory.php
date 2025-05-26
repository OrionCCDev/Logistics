<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch; // For branch_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', '+6 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 years');

        return [
            'name' => $this->faker->bs . ' Project',
            'code' => $this->faker->unique()->bothify('PROJ-#####'),
            'description' => $this->faker->text(200),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $this->faker->optional(0.7)->passthrough($endDate->format('Y-m-d')), // 70% chance of having an end date
            'status' => $this->faker->randomElement(['active', 'completed', 'inactive']),
            // 'branch_id' is added by a different migration.
            // Example for branch_id, if Branch seeder runs first:
            // 'branch_id' => Branch::inRandomOrder()->first()->id ?? Branch::factory()->create()->id,
        ];
    }
}
