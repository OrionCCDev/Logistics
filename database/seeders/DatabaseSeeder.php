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
use Illuminate\Support\Facades\DB;

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

        // Seed suppliers with unique names from provided list
        $supplierNames = [
            'Abdul waheed', 'Ahmad Hassan', 'Ahmed Adil', 'AHMED HASSAN', 'Ahmed Turki',
            'Al Azam Transport', 'Al Bahar transport', 'AL ERFAN', 'AL GHAZI', 'Al Hilal Transport',
            'Al Mafraq', 'Al Mutawa', 'Al Naham', 'Al Nuaimi', 'Al Rasmi', 'Al Sahil',
            'Al Salih Electro mech', 'al Saqar Transport', 'AL SEDRA', 'AL SHAMIL',
            'Al Soqour Transport', 'Al Taj Water', 'Al Tannaf', 'ASIAN TRANSPORT',
            'AZEEM TRANSPORT', 'Bhatia Brothers', 'CDHorison', 'Delta', 'ERC TRANSPORT',
            'Fauji Transport', 'FOUR STAR', 'G Therm', 'Gargash Equipment', 'Genesis',
            'imad transport', 'Jabal Jaish', 'Jhon Son', 'Mazid Building', 'Middle East',
            'Mr.Thamer', 'Muhammad Younis', 'Naffco', 'Oasis', 'OCC', 'omi', 'OMI Transport',
            'Orion', 'Pure Piling', 'Rapid Access', 'Sea Coral', 'Sher Khan',
        ];
        // Remove all old suppliers safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('invoices')->truncate();
        \App\Models\Supplier::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach (array_unique($supplierNames) as $name) {
            $email = strtolower(str_replace([' ', '.', ','], ['_', '', ''], $name)) . '@example.com';
            \App\Models\Supplier::updateOrCreate(
                ['name' => $name],
                [
                    'contact_name' => $name,
                    'contact_email' => $email,
                    'address' => 'N/A',
                    'status' => 'active',
                ]
            );
        }

        // Seed branches with provided data
        $uae = \App\Models\Country::where('code', 'UAE')->first();
        if (!$uae) {
            throw new \Exception('UAE country not found. Please check your CountrySeeder.');
        }
        $branchesData = [
            [
                'name' => 'Main',
                'code' => 'Orion-RAK',
                'country_id' => $uae->id,
                'address' => 'ras al khaima',
                'is_active' => true,
            ],
            [
                'name' => 'Dubai',
                'code' => 'DXB',
                'country_id' => $uae->id,
                'address' => 'Dubai',
                'is_active' => true,
            ],
        ];
        // Remove all old branches and projects safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('project_vehicle')->truncate();
        \App\Models\Project::truncate();
        \App\Models\Branch::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $branches = [];
        foreach ($branchesData as $data) {
            $branches[$data['code']] = \App\Models\Branch::create($data);
            echo "Created branch: {$data['name']}\n";
        }
        // Seed projects with provided data, all linked to Orion-RAK branch
        $projectsData = [
            ['name' => 'All General', 'code' => 'AG-001', 'branch_code' => 'Orion-RAK'],
            ['name' => 'Marjan', 'code' => 'RAK222', 'branch_code' => 'Orion-RAK'],
            ['name' => 'RAK Central', 'code' => 'RAK242', 'branch_code' => 'Orion-RAK'],
            ['name' => 'Ashok Lyland', 'code' => 'RAK245', 'branch_code' => 'Orion-RAK'],
            ['name' => 'Sobha', 'code' => 'RAK246', 'branch_code' => 'Orion-RAK'],
            ['name' => 'sobha', 'code' => 'RAK255', 'branch_code' => 'Orion-RAK'],
            ['name' => 'VIP Villa', 'code' => 'RAK247', 'branch_code' => 'Orion-RAK'],
            ['name' => 'RAKEZ warehouse (60 K)', 'code' => 'RAK248', 'branch_code' => 'Orion-RAK'],
            ['name' => 'RAKEZ warehouse (20 K)', 'code' => 'RAK249', 'branch_code' => 'Orion-RAK'],
            ['name' => 'RAKEZ warehouse (15 K)', 'code' => 'RAK250', 'branch_code' => 'Orion-RAK'],
            ['name' => 'RAKEZ Accommodation', 'code' => 'RAK254', 'branch_code' => 'Orion-RAK'],
            ['name' => 'X-Pro AlGhail', 'code' => 'RAK257', 'branch_code' => 'Orion-RAK'],
            ['name' => 'AL-Bawardy', 'code' => 'SH-007', 'branch_code' => 'Orion-RAK'],
        ];
        foreach ($projectsData as $data) {
            $project = \App\Models\Project::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'branch_id' => $branches[$data['branch_code']]->id ?? null,
                'status' => 'active',
            ]);
            echo "Created project: {$data['name']}\n";
        }

        // Remove all old vehicles and project_vehicle safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('project_vehicle')->truncate();
        \App\Models\Vehicle::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Load all vehicles from JSON file
        $vehiclesData = json_decode(file_get_contents(base_path('public/vehicles_data.json')), true);
        foreach ($vehiclesData as $v) {
            $supplier = \App\Models\Supplier::where('name', $v['supplier_name'])->first();
            if (!$supplier) continue; // skip if supplier not found
            $vehicle = \App\Models\Vehicle::updateOrCreate(
                ['plate_number' => $v['plate_number']],
                [
                    'vehicle_type' => $v['vehicle_type'],
                    'supplier_id' => $supplier->id,
                ]
            );
            echo "Created/updated vehicle: {$v['plate_number']} ({$v['vehicle_type']})\n";
            foreach ($v['projects'] as $projectCode) {
                $project = \App\Models\Project::where('code', $projectCode)->first();
                if ($project) {
                    $vehicle->projects()->syncWithoutDetaching([$project->id]);
                    echo "  Assigned to project: {$projectCode}\n";
                }
            }
        }
    }
}
