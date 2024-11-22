<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Web\BookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Http\Resources\Web\BookCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Traits\BooksCachingTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BookController extends Controller
{

    use BooksCachingTrait, AuthorizesRequests;
    /*
    |--------------------------------------------------------------------------
    | Book Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to books index page.
    |
    */
    
    
    public function index()
    {

        // Get all books with their categories using Eloquent relationship
        $books = Book::orderBy('created_at', 'desc')->with('category')->paginate(5);

        // Convert the books to a resource collection for easy json response
        $books = new BookCollection($books);

        $data = ['books' => $books];

        // Get the current library state
        //This an optional function to get the current library state
        //For the better performance, I'm using cache to store the results and use the cache to store the results
        $libraryState = $this->getLibraryState();

        // Add the library state to the data array
        $data = array_merge($data, $libraryState);
 
        return view('books.index', $data);

    }


    /**
     * Display a form to add a new book
     *
     */
    public function create()
    {
        // Get all categories for the book creation form.
        $categories = Category::all();


        return view('books.create', compact('categories'));
    }


    /**
     * Store a newly created book in the database.
     *
     * Validates the incoming request data and stores a new book record,
     * including uploading the cover image if provided. Logs the book creation
     * and redirects to the books index page with a success message.
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

            Log::info('Book created successfully', [
                'user_id' => Auth::user()->id,
                'book_id' => $book->id
            ]);

            // Delete the library state from the cache
            $this->deleteLibraryStateFromCache();
            
            return redirect()->route('books.index')
                ->with('success', __('book.book_created_successfully'));
                
        } catch (\Exception $e) {

            Log::error('Book creation failed', [
                'user_id' => Auth::user()->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('books.index')
                ->with('error', __('book.book_creation_failed'));
        }
        
    }


    /**
     * Display a form for editing the specified book.
     *
     */
    public function edit(Book $book)
    { 
        // Authorize the user to update the book.
        $this->authorize('update', $book);

        // Get all categories for the book edit form.
        $categories = Category::all();

        return view('books.edit', compact('book', 'categories'));
    }


    /**
     * Update the specified book in the database.
     *
     * Validates the incoming request data and updates a book record,
     * including uploading the cover image if provided. Logs the book update
     * and redirects to the books index page with a success message.
     */
    public function update(BookRequest $request, Book $book)
    {
        // Authorize the user to update the book.
        $this->authorize('update', $book);
        
        $validatedData = $request->validated();
        
        // Check if the title has changed and regenerate the slug
        if (isset($validatedData['title']) && $validatedData['title'] !== $book->title) {
            // Regenerate the slug based on the new title
            $validatedData['slug'] = \Str::slug($validatedData['title']) . '-' . time();
        }

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

        // Update the book record with the validated data
        $isUpdated = $book->update($validatedData);

        if (!$isUpdated) {

            Log::error(
                'Book update failed from the web', [
                'user_id' => Auth::user()->id,
                'book_id' => $book->id
            ]);

            return redirect()->route('books.index')
                ->with('error', __('book.book_update_failed'));
        }
 

        // Log the book update event in the application log.
        Log::info(
            'Book updated from the web', [
            'user_id' => Auth::user()->id,
            'book_id' => $book->id
        ]);

         // Redirect to the books index page with a success message.
        return redirect()->route('books.index')
            ->with('success', __('book.book_updated_successfully'));
 
    }

    public function destroy(Book $book)
    {


        // Authorize the user to delete the book.
        $this->authorize('delete', $book);

        // Delete the cover image if it exists
        //We have to very carefully to not remove the cover image from the storage 😂
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
        $this->deleteLibraryStateFromCache();

        // Redirect to the books index page with a success message.
        return redirect()->route('books.index')
            ->with('success', __('book.book_deleted_successfully'));
    }

    

}