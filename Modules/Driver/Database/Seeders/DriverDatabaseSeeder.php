<?php

namespace Modules\Driver\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Driver\Models\Driver;

class DriverDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driver = Driver::create([
        'full_name'        => 'Ahmed Maher',
        'email'            => 'driver@driver.com',
        'phone'            => '01277239235',
        'national_id'      => '301020000002',
        'driver_license'   => '1234',
        'password'         =>  Hash::make('Driver1234'),
        // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
        'custom_id'        => 'D-001'
        ]);
    }
}
