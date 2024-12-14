<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AdminDatabaseSeeder;
use Modules\Auth\Models\Admin;
use Modules\Place\Database\Seeders\PlaceDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminDatabaseSeeder::class,
            PlaceDatabaseSeeder::class
        ]);

        User::create([
            'first_name' => 'user',
            'last_name' => 'test',
            'custom_id' => 'U-001',
            'email' => 'user@user.com',
            'password' => bcrypt('User1234'),
            'email_verified_at' => now(),
            'phone' =>  '0123456789',
            
        ]);
    }
}
