<?php

namespace Modules\Ticket\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\Models\Ticket;

class TicketDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::insert([
        [
            'custom_id' => 'T-001',
            'qr_code' => '26-1234',
            'points' => '14',
        ],
        [
            'custom_id' => 'T-002',
            'qr_code' => '308-1478',
            'points' => '20',
        ],
        [
            'custom_id' => 'T-003',
            'qr_code' => '26-2222',
            'points' => '14',
        ],
        [
            'custom_id' => 'T-004',
            'qr_code' => '18-1598',
            'points' => '21',
        ],
        [
            'custom_id' => 'T-005',
            'qr_code' => '50-1748',
            'points' => '10',
        ],
    ]);
    }
}
