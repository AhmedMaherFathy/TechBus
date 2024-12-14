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
        Ticket::create([
            'custom_id' => 'T-001',
            'qr_code' => '26-1234', //bus(number,plate)
            'points' => '14',
        ]);
    }
}
