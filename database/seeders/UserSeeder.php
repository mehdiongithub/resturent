<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe', // Change to desired name
            'email' => 'admin@gmail.com', // Change to desired email
            'password' => Hash::make('rootroot'), // Password (hashed)
            'company_id' => '36ebb763-abe2-11ef-b417-4820c88d675f', 
            'store_id' => '461dfa27-abe2-11ef-b417-4820c88d675f', 
        ]);

    }
}
