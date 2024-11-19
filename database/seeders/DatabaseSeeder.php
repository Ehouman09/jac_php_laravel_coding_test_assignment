<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // Call the necessary seeders
        $this->call(UserSeeder::class); //Seed users
        $this->call(CategorySeeder::class);//Seed categories
        $this->call(BookSeeder::class);//Seed books

    }
}