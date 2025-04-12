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
        Bus::insert([[
            'plate_number' => '1245-م ص ر',
            'custom_id' => 'B-001',
            'route_id' => 'R-001', //26
            'driver_id' => 'D-001',
            'ticket_id' => 'T-001',
            'status' => 'active',
        ],
        [
            'plate_number' => '1478-ت س ض',
            'custom_id' => 'B-002',
            'route_id' => 'R-002', //308
            'driver_id' => 'D-002',
            'ticket_id' => 'T-002',
            'status' => 'active',
        ],
        [
            'plate_number' => '2222-م س ق',
            'custom_id' => 'B-003',
            'route_id' => 'R-003', //M9
            'driver_id' => 'D-003',
            'ticket_id' => 'T-003', //26-2222
            'status' => 'active',
        ],
        [
            'plate_number' => '1598- و ا س',
            'custom_id' => 'B-004',
            'route_id' => 'R-004', //18
            'driver_id' => 'D-004',
            'ticket_id' => 'T-004', //18-1598
            'status' => 'active',
        ],
        [
            'plate_number' => '1748-ع غ ف',
            'custom_id' => 'B-003',
            'route_id' => 'R-005', //50
            'driver_id' => 'D-005',
            'ticket_id' => 'T-005', //50-1748
            'status' => 'active',
        ],
        ]);
    }
}
