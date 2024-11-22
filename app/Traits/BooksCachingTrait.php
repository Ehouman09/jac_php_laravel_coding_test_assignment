<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

trait BooksCachingTrait
{
   

    public function cacheBooksListing(int $perPage = 5, int $duration = 30)
    {
        // Retrieve the current page number from the request query string, or default to 1 if not provided
        $page = request()->get('page', 1);

        // Check if the cache version has changed since the last retrieval
        // This is used to generate unique cache keys for each page
        //(defaults to 1 if not set)
        $cacheVersion = Cache::get('books.index.page', 1);


        // Generate the cache key based on the current page number and the cache version
        // Use the cache if available, otherwise retrieve the data from the database and cache it
        $cacheKey = "books.index.v{$cacheVersion}.page.{$page}";

        // If the cache is not available or the cache version has changed, retrieve the data from the database and cache it
        return Cache::remember($cacheKey, now()->addMinutes($duration), function () use ($perPage) {
            return Book::orderBy('created_at', 'desc')
                ->with('category')
                ->paginate($perPage);
        });
    }
    
    
    /**
     * Retrieves the current state of the library.
     * 
     * The state consists of the total number of books, total number of books
     * for the current user, and total number of categories.
     * 
     * The values are cached for 1 hour to prevent excessive database queries.
     * 
     * @return array
     */
    Public function getLibraryState() {
        
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
     * Delete the library state cache.
     * 
     * This method clears the cache when a book is deleted or created to prevent
     * incorrect counts. The cache is cleared for the total number of books, total
     * number of books for the current user, and total number of categories.
     */
    Public function clearBooksCache() { 

        // Let clear the cache when a book is deleted or created to prevent incorrect counts
        Cache::forget('total_books');
        Cache::forget('total_user_books_' . auth()->id());
        // Clear the cache when a categories is deleted or created to prevent incorrect counts
        //Normally, I should not delete the categories cache, but I did it for the better performance
        //And also because we can use this function in the future
        Cache::forget('total_categories');


        // Check if the version key exists, if not, initialize it to 1
        if (!Cache::has('books.index.page')) {
            Cache::put('books.index.page', 1);  // Initialize with 1 if it doesn't exist
        }

        // Increment the version key to invalidate cache
        Cache::increment('books.index.page');
 
    }

}
