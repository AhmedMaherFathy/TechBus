<?php

namespace Modules\Balance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Balance\Models\Balance;

class BalanceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Balance::create([
            'points' => 1000,
            'user_id' => 'P-001'
        ]);
    }
}
