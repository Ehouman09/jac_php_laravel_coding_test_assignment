<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Web\BookRequest;
use App\Traits\JsonResponseTrait;
use App\Models\Book;
use App\Models\Category;
use App\Http\Resources\Web\BookCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;



class BookController extends Controller
{

    // Let call our json response trait
    use JsonResponseTrait;
    /*
    |--------------------------------------------------------------------------
    | Book Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to admin dashboard.
    |
    */
    
    
    public function index()
    {

        // Get all books with their categories using Eloquent relationship
        $books = Book::with('category')->paginate(5);

        // Convert the books to a resource collection for easy json response
        $books = new BookCollection($books);

        $data = ['books' => $books];

        // Get the current library state
        //This an optional function to get the current library state
        //For the better performance, I'm using cache to store the results and use the cache to store the results
        $libraryState = $this->_getLibraryState();

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
        return view('books.create');
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
                ->store('/', 'public');
        }

        //Let create a new book record with the validated data and the cover image path.
        $book = Book::create([
            ...$validatedData,
            'user_id' => Auth::user()->id,
            'cover_image' => $coverPath
        ]);

        // Log the book creation event in the application log.
        Log::info('Book created', ['book_id' => $book->id]);

        // Delete the library state from the cache
        $this->_deleteLibraryStateFromCache();

        // Redirect to the books index page with a success message.
        return redirect()->route('books.index')
            ->with('success', __('book.book_created_successfully'));
    }



    /**
     * Gets the current library state.
     * 
     * This function retrieves several counts related to the library, like the total
     * number of books, the number of books from the current user, and the number of
     * distinct categories. It uses the cache to store the results for 1 minute, to
     * prevent hitting the database too often.
     *
     */
    private function _getLibraryState() {
        
        // Let cache the totals for better performance if not changed often
        $totalBooks = Cache::remember('total_books', 60, function () {
            return Book::count();
        });

        $totalUserBooks = Cache::remember('total_user_books_' . Auth::user()->id, 60, function () {
            return Book::where('user_id', Auth::user()->id)->count();
        });

        $totalCategories = Cache::remember('total_categories', 60, function () {
            return Category::count();
        });

        return [
            'total_books' => $totalBooks,
            'total_user_books' => $totalUserBooks,
            'total_categories' => $totalCategories,
        ];

 
    }


    /**
     * Deletes the cached library state.
     * 
     * This function clears cached values for total books, total books for the current user,
     * and total categories. It should be called when a book is created or deleted to ensure
     * the cache remains accurate.
     */
    private function _deleteLibraryStateFromCache() {
        // Let clear the cache when a book is deleted or created to prevent incorrect counts
        Cache::forget('total_books');
        Cache::forget('total_user_books_' . auth()->id());
        Cache::forget('total_categories');
    }


}