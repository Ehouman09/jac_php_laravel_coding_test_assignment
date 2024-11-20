<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Models\Book;


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
        $books = Book::with('category')
            ->paginate(5);

            dd($books);
        
        return view('books.index', compact('books'));
    }




}