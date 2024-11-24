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
            'company_id' => 'b453ad08-a981-11ef-928a-7a09bb93aba3', 
            'store_id' => 'c6d190b4-a981-11ef-928a-7a09bb93aba3', 
        ]);

    }
}
