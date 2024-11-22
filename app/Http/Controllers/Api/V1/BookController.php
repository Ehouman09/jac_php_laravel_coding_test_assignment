<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\JsonResponseTrait;
use App\Traits\BooksCachingTrait;
use App\Models\Book;
use App\Http\Resources\Api\V1\BookCollection;
use App\Http\Requests\Api\V1\BookRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Api\V1\BookResource;
use Illuminate\Support\Facades\Storage;
use App\Events\BookAddedEvent;

class BookController extends Controller
{

    // Let call our json response trait
    use JsonResponseTrait, BooksCachingTrait, AuthorizesRequests;
    /*
    |--------------------------------------------------------------------------
    | Book Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to books index page.
    |
    */
    
    
    /**
     * Display a paginated list of books with their categories.
     *
     * Retrieves books from the database along with their associated categories,
     * paginates the results, and returns them as a JSON response using the
     * BookCollection resource.
     *
     */
    public function index()
    {
         //For better performance, let load the books list from the cache
        //This improves performance by reducing the number of database queries
        // Get all books with their categories using Eloquent relationship
        //So I commented below because I'm using cache
         //$books = Book::orderBy('created_at', 'desc')->with('category')->paginate(5);

         // Get all books with their categories using cache
        //The cache is stored for 30 minutes by default
        $books = $this->cacheBooksListing();

         // Convert the books to a resource collection for easy json response
         $booksCollection = new BookCollection($books);


        return $this->jsonResponse(200, "book listing", $booksCollection);

    }


    /**
     * Retrieve a single book by its ID.
     *
     * Retrieves a single book instance by its ID, converts it to a resource
     * using the BookResource, and returns it as a JSON response.
     *
     */
    public function show(Book $book)
    {

        $bookResource = new BookResource($book);

        return $this->jsonResponse(200, "book details", $bookResource);
        
    }


    /**
     * Store a newly created book in the database.
     *
     * Validates the incoming request data and stores a new book record,
     * including uploading the cover image if provided. Logs the book creation
     * and returns them as a JSON response.
     *
     */
    public function store(BookRequest $request)
    { 
        
        $validatedData = $request->validated();

        $coverPath = null;


        // If a cover image has been uploaded, It will be stored and update the book record with the path.
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')
                ->store('book_covers', 'public');
        }

        try {
        // Generate slug from title
            $slug = \Str::slug($validatedData['title']). '-' .time();

            //Let create a new book record with the validated data and the cover image path.
            $book = Book::create([
                ...$validatedData,
                'user_id' => Auth::user()->id,
                'cover_image' => $coverPath,
                'slug' => $slug
            ]);

            $bookResource = new BookResource($book);

            /// Emit the book added event
            BookAddedEvent::dispatch($book);

            // Delete the library state from the cache after creating a new book in order to prevent incorrect counts
            $this->clearBooksCache();

            // Return the created book as a JSON response with a 201 status code.
            //201 status code indicates that the request has been fulfilled and that a new resource has been created
            return $this->jsonResponse(201, "book created", $bookResource);

        } catch (\Exception $e) {
                
            Log::error('Book creation failed', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage()
            ]);
            
            return $this->jsonResponse(500, "error", null);
        }

    }


    /**
     * Update the specified book in the database.
     * 
     * Validates the incoming request data and updates a book record,
     * including uploading the cover image if provided. Logs the book update
     * and returns the updated book as a JSON response with a 200 status code.
     * 
     */
    public function update(BookRequest $request, Book $book)
    { 
        // Authorize the user to update the book.
        $this->authorize('update', $book);
        
        $validatedData = $request->validated();
 
        // Check if a cover image is uploaded
        if ($request->hasFile('cover_image')) {
            
            //Delete the cover image if it exists
            if ($book->cover_image) {
                Storage::delete('public/' . $book->cover_image);
            }

            // Store the new cover image and get the file name (not the full path)
        // If a cover image has been uploaded, It will be stored and update the book record with the path.
            $coverPath = $request->file('cover_image')->store('book_covers', 'public');

            $validatedData['cover_image'] = $coverPath; 
        }

        // Check if the title has changed and regenerate the slug
        if ($validatedData['title'] !== $book->title) {
            // Regenerate the slug based on the new title
            $validatedData['slug'] = \Str::slug($validatedData['title']) . '-' . time();
        }

        $isUpdated = $book->update($validatedData);

        //Check if the book has been updated
        if (!$isUpdated) {

            // Log the book update event in the application log.
            Log::error(
                'Book update failed from API', [
                'book_id' => $book->id,
                'user_id' => Auth::user()->id
            ]); 
            return $this->jsonResponse(500, "error", null);
        }
        
        $bookResource = new BookResource($book);

        // Log the book update event in the application log.
        Log::info(
            'Book updated from API', [
            'book_id' => $bookResource->id,
            'user_id' => Auth::user()->id
        ]);

        return $this->jsonResponse(200, "book updated", $bookResource);

 
    }



    /**
     * Delete a book.
     * 
     * This method deletes a book from the database. It uses Laravel's authorization
     * to check if the user is authorized to delete the book. If the book is deleted,
     * the cover image will be deleted as well. The library state cache is also cleared.
     * Finally, a 200 status code is returned with a JSON response containing a success message.
     * 
     */
    public function destroy(Book $book)
    {


        // Authorize the user to delete the book.
        $this->authorize('delete', $book);

        // Delete the cover image if it exists
        if ($book->cover_image) {

            // Get the path to the cover image
            $filePath = 'public/' . $book->cover_image;

            // Let check if the file exists before attempting to delete
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }   

        }

        // Delete the book
        $book->delete();

        // Log the book deletion event in the application log.
        Log::info('Book deletion from API', [
            'user_id' => Auth::user()->id
        ]);

        // Delete the library state from the cache after creating a new book 
        //in order to prevent incorrect counts on the web page
        $this->clearBooksCache();

        return $this->jsonResponse(200, "book deleted", null); 
    }




}