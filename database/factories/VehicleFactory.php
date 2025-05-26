<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supplier; // For supplier_id if you decide to link it here
use App\Models\Operator; // For operator_id if you decide to link it here

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleTypes = ['Sedan', 'SUV', 'Truck', 'Van', 'Bus', 'Motorcycle', 'Excavator', 'Loader', 'Crane'];
        $vehicleMakes = ['Toyota', 'Ford', 'Honda', 'BMW', 'Mercedes', 'Volvo', 'CAT', 'Komatsu'];
        $vehicleModels = ['Camry', 'F-150', 'Civic', 'X5', 'C-Class', 'XC90', '320D', 'WA380'];

        return [
            'plate_number' => $this->faker->bothify('???-####'),
            'vehicle_type' => $this->faker->randomElement($vehicleTypes),
            'vehicle_model' => $this->faker->randomElement($vehicleMakes) . ' ' . $this->faker->randomElement($vehicleModels),
            'vehicle_year' => $this->faker->year,
            'vehicle_image' => 'dashAssets/uploads/vehicles/default_vehicle_image.png', // Default or fake image path
            'vehicle_status' => $this->faker->randomElement(['active', 'inactive', 'maintenance']),
            'vehicle_lpo_number' => $this->faker->optional()->bothify('LPO-#######'),
            'vehicle_lpo_document' => null,
            'vehicle_mulkia_document' => null,
            // supplier_id and operator_id are added by a different migration.
            // These will be handled by the seeder or by configuring relationships in the factory later.
            // Example for supplier_id, if Supplier seeder runs first:
            // 'supplier_id' => Supplier::inRandomOrder()->first()->id ?? Supplier::factory()->create()->id,
            // Example for operator_id, if Operator seeder runs first:
            // 'operator_id' => $this->faker->optional()->passthrough(
            //    Operator::inRandomOrder()->first()->id ?? Operator::factory()->create()->id
            // ),
        ];
    }
}
