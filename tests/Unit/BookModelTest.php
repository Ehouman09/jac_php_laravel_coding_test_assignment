<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BookModelTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Tests that a book can be created in the database.
     *
     * This test verifies that a book can be successfully created with the
     * required data and that the user_id is properly associated with the
     * book.
     */
    #[Test]
    public function it_can_create_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // Create a user
        $category = Category::factory()->create(); // Create a category

        $data = [
            'user_id' => $user->id, // User ID
            'category_id' => $category->id, // Category ID
            'title' => 'My Book',
            'author' => 'Jean Yves',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'my-book',
        ];

        // Act: Create a book
        $book = Book::create($data);

        // Assert: Ensure the book was created
        $this->assertDatabaseHas('books', [
            'title' => 'My Book',
            'author' => 'Jean Yves',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'my-book',
            'user_id' => $user->id, // Check that the user_id is properly set
        ]);
    }

   
    /**
     * Tests that a book belongs to a category.
     *
     * This test verifies the relationship between a book and its associated
     * category, ensuring that the category_id of the book matches the id
     * of the category it is related to.
     */
    #[Test]
    public function it_belongs_to_a_category()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // create a new user
        $category = Category::factory()->create(); // create a new category

        $book = Book::create([
            'user_id' => $user->id, // user id
            'category_id' => $category->id, // category id
            'title' => 'My Book',
            'author' => 'Jean Yves',
            'description' => 'A test book.',
            'publication_year' => 2024,
            'slug' => 'my-book',
        ]);

        // Act: Check the relationship
        $this->assertEquals($category->id, $book->category->id);
    }

 
    /**
     * Tests that a book can be updated.
     *
     * This test verifies that a book can be successfully updated in the
     * database. It ensures that the book's title, author, description, publication
     * year, and slug are all updated correctly.
     */
    #[Test]
    public function it_can_update_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create();//Create a new user
        $category = Category::factory()->create();//Create a  new category
        $book = Book::create([
            'user_id' => $user->id, 
            'category_id' => $category->id,
            'title' => 'My new book',
            'author' => 'Jean Yves',
            'description' => 'A test book.',
            'publication_year' => 2023,
            'slug' => 'my-new-book',
        ]);

        // Act: Update the book
        $book->update([
            'title' => 'My new book updated',
            'author' => 'Ehouman',
            'description' => 'A test book updated description.',
            'publication_year' => 2024,
            'slug' => 'my-new-book-updated',
        ]);

        // Assert: Ensure the book was updated
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'My new book updated',
            'author' => 'Ehouman',
            'description' => 'A test book updated description.',
            'publication_year' => 2024,
            'slug' => 'my-new-book-updated',
        ]);
    }

 
    /**
     * Tests that a book can be deleted from the database.
     *
     * This test verifies that a book can be successfully deleted and
     * ensures that the book is soft deleted by checking the soft deleted state
     * in the database.
     */
    #[Test]
    public function it_can_delete_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // create a new user
        $category = Category::factory()->create(); // create a new category
        
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


    /**
     * Tests that a book can be soft deleted from the database.
     *
     * This test verifies that a book can be successfully soft deleted and
     * ensures that the book is soft deleted by checking the soft deleted state
     * in the database.
     */
    #[Test]
    public function it_can_soft_delete_a_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // create a new user
        $category = Category::factory()->create(); // create a new category

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


    /**
     * Test that a soft-deleted book can be restored.
     *
     * This test verifies that a book, once soft-deleted, can be successfully
     * restored back to its original state in the database. It ensures that
     * the book's data is accurate and matches the initial attributes.
     */
    #[Test]
    public function it_can_restore_a_soft_deleted_book()
    {
        // Arrange: Create necessary data
        $user = User::factory()->create(); // create a new user
        $category = Category::factory()->create(); // create a new category

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
