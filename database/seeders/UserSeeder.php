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
            'company_id' => '0e7eb4c8-a80f-11ef-9cc0-f1ae94da8b26', 
            'store_id' => '55d68680-a80f-11ef-9cc0-f1ae94da8b26', 
        ]);

    }
}
