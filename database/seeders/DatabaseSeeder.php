<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AdminDatabaseSeeder;
use Modules\Balance\Database\Seeders\BalanceDatabaseSeeder;
use Modules\Bus\Database\Seeders\BusDatabaseSeeder;
use Modules\Driver\Database\Seeders\DriverDatabaseSeeder;
use Modules\Place\Database\Seeders\PlaceDatabaseSeeder;
use Modules\Ticket\Database\Seeders\TicketDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'kholoud',
            'last_name' => 'khalid',
            'custom_id' => 'P-001',
            'email' => 'user@user.com',
            'password' => bcrypt('User1234'),
            'email_verified_at' => now(),
            'phone' =>  '0123456789',
        ]);

        $this->call([
            AdminDatabaseSeeder::class,
            PlaceDatabaseSeeder::class,
            TicketDatabaseSeeder::class,
            DriverDatabaseSeeder::class,
            BusDatabaseSeeder::class,
            BalanceDatabaseSeeder::class,
        ]);
        
    }
}
