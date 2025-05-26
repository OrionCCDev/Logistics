<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Vehicle;
use App\Models\Operator;
use App\Models\Project;
use App\Models\Category; // For category_id on Supplier
use App\Models\Country;  // For country_id on Branch
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call existing seeders first to ensure base data like countries and categories exist
        $this->call([
            CountrySeeder::class,
            CategorySeeder::class,
            // Add other essential seeders here if they create prerequisite data
        ]);

        // Create admin role and user (existing logic)
        $adminRole = Role::firstOrCreate([
            'name' => 'orionAdmin',
            'display_name' => 'Orion Admin',
            'description' => 'Orion Administrator'
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'superadmin@orionlogistics.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Orion@2025'),
                'email_verified_at' => now(),
                'role' => 'orionAdmin',
            ]
        );
        Employee::firstOrCreate(
            ['user_id' => $admin->id], // Assuming user_id is unique for employees
            [
                'emp_code' => '001',
                'image' => 'dashAssets/uploads/users/default.png',
                'is_active' => true,
            ]
        );

        // Create instances using factories
        $categories = Category::all();
        if ($categories->isEmpty()) {
            // Fallback if CategorySeeder didn't run or create any
            $categories = Category::factory(3)->create();
        }

        $countries = Country::all();
        if ($countries->isEmpty()) {
            // Fallback if CountrySeeder didn't run or create any
            $countries = Country::factory(5)->create();
        }

        $suppliers = Supplier::factory(10)->make()->each(function ($supplier) use ($categories) {
            $supplier->category_id = $categories->random()->id;
            $supplier->save();
        })->collect();

        $branches = Branch::factory(5)->make()->each(function ($branch) use ($countries) {
            $branch->country_id = $countries->random()->id;
            $branch->save();
        })->collect();

        $operators = Operator::factory(15)->make()->each(function ($operator) use ($suppliers) {
            if ($suppliers->isNotEmpty()) {
                $operator->supplier_id = $suppliers->random()->id;
            }
            // vehicle_id on operator is often nullable or set when assigned,
            // so we might not set it here or make it optional.
            $operator->save();
        })->collect();

        $vehicles = Vehicle::factory(20)->make()->each(function ($vehicle) use ($suppliers, $operators) {
            if ($suppliers->isNotEmpty()) {
                $vehicle->supplier_id = $suppliers->random()->id;
            }
            // operator_id on vehicle can also be tricky; a vehicle might not have an operator initially,
            // or an operator might be assigned to multiple vehicles (or vice-versa depending on relationship type)
            // For a simple nullable one-to-one or one-to-many (vehicle has one operator):
            if ($operators->isNotEmpty() && rand(0,1)) { // 50% chance of assigning an operator
                 $vehicle->operator_id = $operators->random()->id;
            }
            $vehicle->save();
        })->collect();

        $projects = Project::factory(8)->make()->each(function ($project) use ($branches) {
            if ($branches->isNotEmpty()) {
                $project->branch_id = $branches->random()->id;
            }
            $project->save();
        })->collect();

        // Assign vehicles to projects (Many-to-Many through project_vehicles table)
        // This assumes your Project model has a vehicles() BelongsToMany relationship defined.
        if ($vehicles->isNotEmpty()) {
            foreach ($projects as $project) {
                $project->vehicles()->attach(
                    $vehicles->random(rand(1, min(5, $vehicles->count())))->pluck('id')->toArray()
                );
            }
        }
    }
}
