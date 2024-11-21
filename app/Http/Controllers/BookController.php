<?php

namespace App\Http\Controllers;

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




class BookController extends Controller
{

    use BooksCachingTrait;
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
        $books = Book::with('category')->paginate(5);

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
        Log::info('Book created from the web', ['book_id' => $book->id]);

        // Delete the library state from the cache
        $this->deleteLibraryStateFromCache();

        // Redirect to the books index page with a success message.
        return redirect()->route('books.index')
            ->with('success', __('book.book_created_successfully'));
    }



    


}