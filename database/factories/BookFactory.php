<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BookFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // get our created user by his email (admin@demo.com) 
         //$user = User::where('email', 'admin@demo.com')->first();

        return [
            //'user_id' => $user->id, // Let assign the user to the book
            'title' => fake()->sentence(3),  
            'author' => fake()->name, 
            'description' => fake()->text(150), 
            'publication_year' => fake()->year,  
            'slug' => fake()->slug, 
            'created_at' => now(),
            'updated_at' => now(),
 
        ];
    }
 
}
