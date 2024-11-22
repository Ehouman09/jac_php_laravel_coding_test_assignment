<?php

namespace App\Listeners;

use App\Events\BookAddedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogBookAddedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookAddedEvent $event): void
    {
        // Log the book creation event in the application log
        Log::info('Book created successfully', [
            'user_id' => Auth::user()->id,
            'book_id' => $event->book->id
        ]);
        
    }
}
