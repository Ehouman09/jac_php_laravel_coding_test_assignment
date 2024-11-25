<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Generator;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     */
    
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    { 
        // Create an array of categories
        // Each category has an ID, name, and slug
        // The ID is generated using the Str::uuid() method
        $categories = [
            ['id' => Str::uuid(), 'name' => 'Fiction', 'slug' => 'fiction', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'Non-fiction', 'slug' => 'non-fiction', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'Science Fiction', 'slug' => 'science-fiction', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'Manga', 'slug' => 'manga', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'Biography', 'slug' => 'biography', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insert the categories into the database using the insert method
        Category::insert($categories);
 
        
 
        
    }
 
}

