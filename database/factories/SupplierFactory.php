<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category; // Assuming you have a Category model for category_id

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'logo_path' => 'dashAssets/uploads/suppliers/default_supplier_logo.png', // Default or fake image path
            'trade_license_path' => null, // Or $this->faker->imageUrl() if you want fake images
            'vat_certificate_path' => null,
            'statement_path' => null,
            'contact_name' => $this->faker->name,
            'contact_email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'description' => $this->faker->paragraph,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            // 'category_id' is added by a different migration, handle in seeder or if CategoryFactory exists
            // For now, we can assume a default or fetch one if Category seeder runs first.
            // If you have a CategoryFactory and want to create one:
            // 'category_id' => Category::factory(),
            // Or pick a random existing one, assuming they are seeded before suppliers:
            // 'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory()->create()->id,
        ];
    }
}
