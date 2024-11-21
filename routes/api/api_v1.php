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

//Protected routes -> user must be logged in to access
Route::middleware(['auth:sanctum', 'securityHeaders'])->prefix('books')->group(function () {

        Route::get('/', [BookController::class, 'index']);
        Route::get('/{book}', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::put('/{book}', [BookController::class, 'update']);
        Route::delete('/{book}', [BookController::class, 'destroy']);


});
