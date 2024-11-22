<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;


class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    
    /**
         * Tests that an authenticated user can create a book via API.
         *
         * This test verifies that when an authenticated user makes a POST request
         * to the API with valid book data, the book is created and stored in the
         * database and the cover image is stored in the correct directory. The
         * test also checks that the API returns a 201 Created response with the
         * correct structure and data.
         */
    #[Test]
    public function test_user_can_create_book()
    {

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
            'author' => 'Jean Yves',
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
            'author' => 'Jean Yves',
            'description' => 'Test Description',
            'publication_year' => 2024,
        ]);

        // Assert that the cover image is stored in the correct directory
        $book = Book::latest()->first();
        Storage::disk('public')->assertExists($book->cover_image);
    }

    /**
         * Tests that a user cannot update a book created by another user via API
         *
         * This test verifies that when an authenticated user makes a PUT request
         * to the API to update a book created by another user, the request is
         * forbidden and the book remains unchanged in the database.
         */
    #[Test]
     public function test_user_cannot_update_others_book()
    {
        
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
    
     /**
         * Tests that a user cannot delete a book created by another user via API
         *
         * This test verifies that when a user tries to delete a book created by
         * another user, the request is forbidden and the book remains unchanged
         * in the database.
         */
    #[Test]
    public function test_user_cannot_delete_others_book()
    {
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
