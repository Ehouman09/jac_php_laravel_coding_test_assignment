<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\BookController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|I'm using Sanctum for Authentication
|
*/

Route::post('/login', [LoginController::class, 'login'])->name('api.login');


Route::prefix('books')->group(function () {

        Route::middleware(['auth:sanctum', 'securityHeaders'])->group(function () {

             // Create a new book
            Route::post('/', [BookController::class, 'store']);

             // update a book
             Route::put('/{book}', [BookController::class, 'update']);

            // returns a lists of books in the database
            Route::get('/', [BookController::class, 'index']);

            // Display a single book details 
            Route::get('/{book}', [BookController::class, 'show']);


            // Delete a book
            Route::delete('/{book}', [BookController::class, 'destroy']);



        });

    });


Route::middleware(['auth:sanctum', 'securityHeaders'])->group(function () {
    //logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('api.logout');;
});