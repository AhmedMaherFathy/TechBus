<?php

namespace Modules\Notification\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\Notifications\OrderShipped',
                'notifiable_type' => 'Modules\Driver\Models\User',
                'notifiable_id' => 1, 
                'data' => "your trip starts now please open your location",
                'read_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
