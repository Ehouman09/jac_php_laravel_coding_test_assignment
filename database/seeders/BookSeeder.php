<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Faker\Generator;
use Illuminate\Support\Facades\Storage;

class BookSeeder extends Seeder
{
    use HasFactory;
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    { 

 
        //let get our created user by his email (admin@demo.com) 
        $user = User::where('email', 'admin@demo.com')->first();

        //Let use faker to create 10 books
        for ($i = 0; $i < 10; $i++) {

            Book::create([
                'user_id' => $user->id, // Let assign the user to the book
                'title' => $faker->sentence(3),  
                'author' => $faker->name, 
                'description' => $faker->text(150), 
                'publication_year' => $faker->year,  
                'slug' => $faker->slug, 
                'cover_image' => "default-cover-image.png",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        

    }

    
}
