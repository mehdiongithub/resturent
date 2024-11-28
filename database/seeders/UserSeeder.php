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
            'company_id' => 'd38b03eb-ad3f-11ef-8cd9-bb48383d8d0b', 
            'store_id' => 'f2df56a1-ad3f-11ef-8cd9-bb48383d8d0b', 
        ]);

    }
}
