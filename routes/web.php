<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
//use App\Http\Controllers\CategoryController;


//This is the route page of our application
Route::get('/', [LoginController::class, 'showLoginForm']);

Route::middleware(['web'])->group(function () {

    //This route is use to display the login page
    Route::get('/login', [LoginController::class, 'showLoginForm']);
    //To login
    Route::post('login', [LoginController::class, 'login'])->name('login');

    //This route is use to display the register page
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    //To register
    Route::post('register', [RegisterController::class, 'register']);
    
});

//Protected routes, requires authentication -> user must be logged in to access
Route::middleware(['web', 'auth'])->group(function () {

    //This route is use to display the dashboard page
    //Route::get('dashboard', [BookController::class, 'dashboard'])->name('dashboard');
  

    Route::get('/', [BookController::class, 'index'])->name('books.index');
    // returns the form for adding a book
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    // adds a book to the database
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    // returns a page that shows a full book
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    // returns the form for editing a book
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    // updates a book
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    // deletes a book
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    
    /*
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('dashboard');
        Route::get('/{id}', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);
    });
    */

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');


});




  