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
        Driver::insert(
            [
                [
                    'full_name'        => 'Ahmed Maher',
                    'email'            => 'driver@driver.com',
                    'phone'            => '01277239235',
                    'national_id'      => '301020000002',
                    'driver_license'   => '165468916156',
                    'password'         =>  Hash::make('Driver1234'),
                    // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
                    'custom_id'        => 'D-001'
                ],
                [
                    'full_name'        => 'Abdelkodous Fathy',
                    'email'            => 'driver2@driver.com',
                    'phone'            => '01213513546',
                    'national_id'      => '301021111112',
                    'driver_license'   => '13564684',
                    'password'         =>  Hash::make('Driver2345'),
                    // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
                    'custom_id'        => 'D-002'
                ],
                [
                    'full_name'        => 'Mostafa Ali',
                    'email'            => 'driver3@driver.com',
                    'phone'            => '01568496',
                    'national_id'      => '3010201568494',
                    'driver_license'   => '1234687',
                    'password'         =>  Hash::make('Driver3456'),
                    // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
                    'custom_id'        => 'D-003'
                ],
                [
                    'full_name'        => 'Hossam AbdElRahman',
                    'email'            => 'driver4@driver.com',
                    'phone'            => '011534988',
                    'national_id'      => '301022351651',
                    'driver_license'   => '1658495',
                    'password'         =>  Hash::make('Driver4567'),
                    // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
                    'custom_id'        => 'D-004'
                ],
                [
                    'full_name'        => 'Seif Hossam',
                    'email'            => 'driver5@driver.com',
                    'phone'            => '0101234985',
                    'national_id'      => '301020489165',
                    'driver_license'   => '16769489',
                    'password'         =>  Hash::make('Driver5678'),
                    // 'photo'            => 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1734164716/axqienltbo49ee3ty81x.jpg',
                    'custom_id'        => 'D-005'
                ],
            ]
        );
    }
}
