<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_user_can_create_book_api()
    {
        //Tests that an authenticated user can create a book via API.
        // Fake the storage to prevent actual file saving
        Storage::fake('public');

        // Create a fake image for the book cover
        $image = UploadedFile::fake()->image('test-image.jpg');

        // Create a user and authenticate via Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Prepare book data
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'publication_year' => 2024,
            'cover_image' => $image,
        ];

        // Make the POST request to the API
        $response = $this->postJson('/api/v1/books', $bookData);

        // Assert the response status is 201 Created
        $response->assertStatus(201);

        // Assert the response structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'author',
                'description',
                'publication_year',
                'cover_image',
            ],
        ]);

        // Assert the book is stored in the database
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
        public function test_user_cannot_update_book_with_invalid_token_api()
        {
            $user = User::factory()->create();
            $book = Book::factory()->create(['user_id' => $user->id]);
            
            // Store original title to verify nothing changed
            $originalTitle = $book->title;
            
            // Make request without authentication
            $response = $this->putJson("/api/v1/books/{$book->id}", [
                'title' => 'Updated Title',
                'author' => $book->author,
                'description' => $book->description,
                'publication_year' => $book->publication_year
            ]);
            
            // Assert HTTP status
            $response->assertUnauthorized(); // 401 response
            
            // Assert response structure matching your JsonResponseTrait format
            $response->assertJson([
                'code' => 401,
                'status' => 'error',
                'message' => 'Please log in to continue.',
                'data' => null
            ]);
            
            // Verify the book wasn't modified in the database
            $this->assertDatabaseHas('books', [
                'id' => $book->id,
                'title' => $originalTitle,
                'user_id' => $user->id
            ]);
        }

    #[Test]
     public function test_user_cannot_update_others_book_api()
    {
        // Tests that a user cannot update a book created by another user via API
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a book for the first user
        $book = Book::factory()->create(['user_id' => $user1->id]);

        // Store original title to verify nothing changed
        $originalTitle = $book->title;

        // Authenticate as the second user using Sanctum
        Sanctum::actingAs($user2);

        // Try to update the book via API
        $response = $this->putJson("/api/v1/books/{$book->id}", [
            'title' => 'Updated Title',
            'author' => $book->author,
            'description' => $book->description,
            'publication_year' => $book->publication_year
        ]);

        // Assert that the request was forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'code' => 403,
            'status' => 'error',
            'message' => 'You are not authorized to perform this action, your are not the owner.',
            'data' => null
        ]);

        // Verify the book wasn't modified in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => $originalTitle,
            'user_id' => $user1->id
        ]);
    }
    
    #[Test]
    public function test_user_cannot_delete_others_book_api()
    {
        //Tests that a user cannot delete a book created by another user via API
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a book for the first user
        $book = Book::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Original Title',
            'author' => 'Original Author',
            'description' => 'Original Description',
            'publication_year' => 2023
        ]);

        // Authenticate as the second user using Sanctum
        Sanctum::actingAs($user2);

        // Try to delete the book via API
        $response = $this->deleteJson("/api/v1/books/{$book->id}");

        // Assert that the request was forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'code' => 403,
            'status' => 'error',
            'message' => 'You are not authorized to perform this action, your are not the owner.',
            'data' => null
        ]);

        // Assert that the book still exists in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Original Title',
            'user_id' => $user1->id
        ]);
    }
}
