<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
//use App\Http\Controllers\BookController;
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
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

 
    /*
    Route::prefix('books')->group(function () {
        Route::get('/', [ApiBookController::class, 'index']);
        Route::get('/{id}', [ApiBookController::class, 'show']);
        Route::post('/', [ApiBookController::class, 'store']);
        Route::put('/{id}', [ApiBookController::class, 'update']);
        Route::delete('/{id}', [ApiBookController::class, 'destroy']);
    });*/


});




  