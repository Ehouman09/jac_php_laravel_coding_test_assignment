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

Route::post('/login', [LoginController::class, 'login'])->name('login');

//Protected routes -> user must be logged in to access
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('books')->group(function () {

        Route::get('/', [BookController::class, 'index']);
        //Route::get('/{id}', [BookController::class, 'show']);
        //Route::post('/', [BookController::class, 'store']);
        //Route::put('/{id}', [BookController::class, 'update']);
        //Route::delete('/{id}', [BookController::class, 'destroy']);

    });

});
