<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;
 
    /**
     * Tests that a user can create a book via the web interface.
     *
     * This test simulates the creation of a book by a user, including
     * uploading a cover image. It verifies that the book data is correctly
     * stored in the database and that the cover image is saved in the
     * expected directory. The test also checks that the user is redirected
     * to the appropriate page after successfully creating the book.
     */
    #[Test]
     public function test_user_can_create_book()
    {
        // Fake the storage to prevent actual file saving
        Storage::fake('public');

        // Create a fake image for the book cover
        $image = UploadedFile::fake()->image('test-image.jpg');

        // Create a user
        $user = User::factory()->create(); // create a new user
        $this->actingAs($user); // authenticate the user


        $bookData = [
            'user_id' => $user->id,
            'title' => 'Test Book',
            'author' => 'Jean Yves',
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
            'author' => 'Jean Yves',
            'description' => 'Test Description',
            'publication_year' => 2024,
        ]);

        // Assert that the cover image is stored in the correct directory
        $book = Book::latest()->first();
        Storage::disk('public')->assertExists($book->cover_image);
    }

        /**
         * Tests that a user cannot update a book created by another user
         * via the web interface.
         *
         * This test verifies that when a user tries to update a book
         * created by another user, they are redirected to the books page
         * and a warning message is flashed to the session.
         */
    #[Test]
    public function test_user_cannot_update_others_book_web()
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
                'title' => 'Updated Title', // Try to update the title
                'author' => $book->author,  
                'description' => $book->description,
                'publication_year' => $book->publication_year,
            ]);

            // Assert that the user is not allowed to update the book
            $response->assertRedirect();
            $response->assertSessionHas('warning', 'You are not authorized to perform this action, your are not the owner.');
        }
        
        /**
         * Tests that a user cannot delete a book created by another user
         * via the web interface.
         *
         * This test verifies that when a user tries to delete a book
         * created by another user, they are redirected to the books page
         * and a warning message is flashed to the session. The test also
         * checks that the book still exists in the database.
         */
    #[Test]
    public function test_user_cannot_delete_others_book_web()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create a book for the first user and delete it for the second user
        $book = Book::factory()->create([
            'user_id' => $user1->id,
            'title' => 'My Test Book',
            'author' => 'Jean Yves',
            'description' => 'A test book.',
            'publication_year' => 2023
        ]);

        // Login as the second user
        $this->actingAs($user2);
        $response = $this->delete("/books/{$book->id}");


        // Assert that the user is not allowed to delete the book
        $response->assertRedirect();
        $response->assertSessionHas('warning', 'You are not authorized to perform this action, your are not the owner.');

        // Assert that the book still exists in the database
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'My Test Book'
        ]);
    } 

}