<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier; // For supplier_id
use App\Models\Vehicle;  // For vehicle_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 */
class OperatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'image' => 'dashAssets/uploads/operators/default_operator_image.png',
            'front_license_image' => null, // Or $this->faker->imageUrl()
            'back_license_image' => null,  // Or $this->faker->imageUrl()
            'status' => $this->faker->randomElement(['active', 'inactive', 'on_leave']),
            'license_number' => $this->faker->unique()->bothify('LIC-########'),
            'license_expiry_date' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
            // supplier_id and vehicle_id are added by a different migration.
            // These will be handled by the seeder or by configuring relationships in the factory later.
            // Example for supplier_id (can be nullable):
            // 'supplier_id' => $this->faker->optional()->passthrough(
            //    Supplier::inRandomOrder()->first()->id ?? Supplier::factory()->create()->id
            // ),
            // Example for vehicle_id (can be nullable):
            // 'vehicle_id' => $this->faker->optional()->passthrough(
            //    Vehicle::inRandomOrder()->first()->id ?? Vehicle::factory()->create()->id
            // ),
        ];
    }
}
