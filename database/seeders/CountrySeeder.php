<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'name' => 'United Arab Emirates',
                'code' => 'UAE',
                'phone_code' => '971',
                'currency' => 'AED',
                'currency_symbol' => 'د.إ',
                'is_active' => true,
            ],
            [
                'name' => 'Egypt',
                'code' => 'EGY',
                'phone_code' => '20',
                'currency' => 'EGP',
                'currency_symbol' => '£',
                'is_active' => true,
            ],
            [
                'name' => 'Oman',
                'code' => 'OMN',
                'phone_code' => '968',
                'currency' => 'OMR',
                'currency_symbol' => 'ر.ع.',
                'is_active' => true,
            ],
            [
                'name' => 'Jordan',
                'code' => 'JOR',
                'phone_code' => '962',
                'currency' => 'JOD',
                'currency_symbol' => 'JD',
                'is_active' => true,
            ],
            [
                'name' => 'Saudi Arabia',
                'code' => 'SAU',
                'phone_code' => '966',
                'currency' => 'SAR',
                'currency_symbol' => '﷼',
                'is_active' => true,
            ],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->updateOrInsert(
                ['code' => $country['code']],
                $country
            );
        }
    }
}
