<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

class BookWebTest extends TestCase
{
    use RefreshDatabase;
 
    #[Test]
     public function test_user_can_create_book()
    {
        //Tests that a user can create a book

        // Fake the storage to prevent actual file saving
        Storage::fake('public');

        // Create a fake image for the book cover
        $image = UploadedFile::fake()->image('test-image.jpg');

        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);


        $bookData = [
            'user_id' => $user->id,
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'publication_year' => 2024,
            'cover_image' => $image,
        ];

        // Make the POST request to store the book
        $response = $this->post('/books', $bookData);

        // Assert that the user is redirected to the books page after creating the book
        $response->assertRedirect('/books');
        
        // Assert the book was stored in the database
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'publication_year' => 2024,
        ]);

        // Assert that the cover image is stored in the correct directory
        $book = Book::latest()->first();
        Storage::disk('public')->assertExists($book->cover_image);
    }

    #[Test]
    public function test_user_cannot_update_others_book_web()
        {
            //Tests that a user cannot update a book created by another user
            //\App\Http\Controllers\Web\BookController::update


            // Create two users
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            // Create a book for the first user and try to update it for the second user
            $book = Book::factory()->create(['user_id' => $user1->id]);

            // Login as the second user
            $this->actingAs($user2);

            // Try to update the book
            $response = $this->put("/books/{$book->id}", [
                'title' => 'Updated Title',
                'author' => $book->author,  // Add required fields
                'description' => $book->description,
                'publication_year' => $book->publication_year
            ]);

            // Assert that the user is not allowed to update the book
            $response->assertRedirect();
            $response->assertSessionHas('warning', 'You are not authorized to perform this action, your are not the owner.');
        }
        
    #[Test]
    public function test_user_cannot_delete_others_book_web()
    {
        //Tests that a user cannot delete a book created by another user
        //\App\Http\Controllers\Web\BookController::destroy


        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a book for the first user and delete it for the second user
        $book = Book::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Original Title',
            'author' => 'Original Author',
            'description' => 'Original Description',
            'publication_year' => 2023
        ]);

        // Login as the second user
        $this->actingAs($user2);
        $response = $this->delete("/books/{$book->id}");


        $response->assertRedirect();
        $response->assertSessionHas('warning', 'You are not authorized to perform this action, your are not the owner.');

        // Assert that the book still exists in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Original Title'
        ]);
    } 

}