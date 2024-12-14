<?php

namespace Modules\Bus\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bus\Models\Bus;

class BusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bus::create([
            'plate_number' => '1245',
            'custom_id' => 'B-001',
            'route_id' => 'R-001',
            'driver_id' => 'D-001',
            'ticket_id' => 'T-001',
            'status' => 'active',
        ]);
    }
}
