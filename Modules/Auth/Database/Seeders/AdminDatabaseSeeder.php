<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\Admin;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([
        Admin::create([
            'first_name' => 'Ahmed',
            'last_name' => 'Ahmed',
            'phone' => '+01277239235',
            'email' => 'ahmedmaher@admin.com',
            'password' => Hash::make('123456789'),
            'custom_id' => 'A-001',
        ]);
        // ]);
    }
}
