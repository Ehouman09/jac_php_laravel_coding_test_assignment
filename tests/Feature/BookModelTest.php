<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookModelTest extends TestCase
{
    use RefreshDatabase;

    #[test]
    public function it_can_create_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // Ensure a user is created
        $category = Category::factory()->create(); // Create a category

        $data = [
            'user_id' => $user->id, // Set user_id here
            'category_id' => $category->id, 
            'title' => 'Sample Book',
            'author' => 'John Doe',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'sample-book',
        ];

        // Act: Create a book
        $book = Book::create($data);

        // Assert: Ensure the book was saved to the database
        $this->assertDatabaseHas('books', [
            'title' => 'Sample Book',
            'author' => 'John Doe',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'sample-book',
            'user_id' => $user->id, // Check that the user_id is properly set
        ]);
    }

    #[test]
    public function it_belongs_to_a_category()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id, 
            'title' => 'Sample Book',
            'author' => 'John Doe',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'sample-book',
        ]);

        // Act: Check the relationship
        $this->assertEquals($category->id, $book->category->id);
    }

    #[test]
    public function it_can_update_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id,
            'title' => 'Original Title',
            'author' => 'Original Author',
            'description' => 'Original Description',
            'publication_year' => 2022,
            'slug' => 'original-title',
        ]);

        // Act: Update the book
        $book->update([
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'publication_year' => 2023,
            'slug' => 'updated-title',
        ]);

        // Assert: Ensure the book was updated
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'publication_year' => 2023,
            'slug' => 'updated-title',
        ]);
    }

    #[test]
    public function it_can_delete_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id,
            'title' => 'Book to Delete',
            'author' => 'Author to Delete',
            'description' => 'Description to Delete',
            'publication_year' => 2024,
            'slug' => 'book-to-delete',
        ]);

        // Act: Delete the book
        $book->delete();

        // Assert: Ensure the book was deleted
        $this->assertSoftDeleted('books', [
            'id' => $book->id,
            'title' => 'Book to Delete',
        ]);
    }

    #[test]
    public function it_can_soft_delete_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id,
            'title' => 'Book to Soft Delete',
            'author' => 'Author to Soft Delete',
            'description' => 'Description to Soft Delete',
            'publication_year' => 2024,
            'slug' => 'book-to-soft-delete',
        ]);

        // Act: Soft delete the book
        $book->delete();

        // Assert: Ensure the book is soft deleted
        $this->assertSoftDeleted($book);
    }

    #[test]
    public function it_can_restore_a_soft_deleted_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id,
            'title' => 'Book to Restore',
            'author' => 'Author to Restore',
            'description' => 'Description to Restore',
            'publication_year' => 2024,
            'slug' => 'book-to-restore',
        ]);

        // Act: Soft delete and then restore the book
        $book->delete();
        $book->restore();

        // Assert: Ensure the book was restored
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Book to Restore',
            'author' => 'Author to Restore',
            'description' => 'Description to Restore',
            'publication_year' => 2024,
            'slug' => 'book-to-restore',
        ]);
    }
}
