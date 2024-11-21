<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Book;
use App\Policies\BookPolicy;

class AuthServiceProvider extends ServiceProvider
{


    //Let define the policies for the application.
    protected $policies = [
        Book::class => BookPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
