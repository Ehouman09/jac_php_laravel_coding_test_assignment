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

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that a user can create a book
     *
     * @covers \App\Http\Controllers\Web\BookController::store
     */
     public function test_user_can_create_book()
    {

        // Fake the storage to prevent actual file saving
        Storage::fake('public');

        // Create a fake image for the book cover
        $image = UploadedFile::fake()->image('test-image.jpg');

        // Create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Generate a unique slug
        $slug = 'test-book-' . time();

        $bookData = [
            'user_id' => $user->id,
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'slug' => $slug,
            'publication_year' => 2023,
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
            'slug' => $slug,
            'publication_year' => 2023,
        ]);

        // Assert that the cover image is stored in the correct directory
        $book = Book::latest()->first();
        Storage::disk('public')->assertExists($book->cover_image);
    }

    /**
     * Tests that a user cannot update a book created by another user
     *
     * @covers \App\Http\Controllers\Web\BookController::update
     */
   public function test_user_cannot_update_others_book()
    {
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
        $response->assertStatus(403);
    }


    /**
     * Tests that a user cannot delete a book created by another user
     *
     * @covers \App\Http\Controllers\Web\BookController::destroy
     */
    public function user_cannot_delete_others_book()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a book for the first user and delete it for the second user
        $book = Book::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Original Title',
            'author' => 'Original Author',
            'description' => 'Original Description',
            'publication_year' => 2022
        ]);

        // Login as the second user
        $this->actingAs($user2);
        $response = $this->delete("/books/{$book->id}");

        // Assert that the user is not allowed to delete the book
        $response->assertStatus(403);
        // Assert that the book still exists in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Original Title'
        ]);
    }

}