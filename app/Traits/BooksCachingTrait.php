<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

trait BooksCachingTrait
{
   
    
    
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
    Public function deleteLibraryStateFromCache() {
        // Let clear the cache when a book is deleted or created to prevent incorrect counts
        Cache::forget('total_books');
        Cache::forget('total_user_books_' . auth()->id());
        Cache::forget('total_categories');
    }

}
