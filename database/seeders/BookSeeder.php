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
                'cover_image' => $this->generateFakeImage($faker),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
        

    }

    /**
 * Let generate a random image using Faker and store it in the `public` disk.
 *
 * @return string
 */

private function generateFakeImage(Generator $faker)
{
    //  Fist:
    //      ->let generate a random image and move it to storage/public/images/books
    $imagePath = $faker->image('public/images/books'); // Temporary image using Faker
    $fileName = basename($imagePath); // We will get the file name

    // Second:
    //      ->let move the image to storage/public/images/books and get the storage-relative path
    $storagePath = 'images/books/' . $fileName;
    Storage::disk('public')->put($storagePath, file_get_contents($imagePath));

    // Clean up the temp image
    unlink($imagePath);

    // Let return the storage-relative path in order to store it in the database
    return $storagePath;
}
}
