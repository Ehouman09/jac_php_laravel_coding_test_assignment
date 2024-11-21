<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine whether the user can update the given book.
     */
    public function update(User $user, Book $book)
    {
        return $user->id === $book->user_id;
    }

    /**
     * Determine whether the user can delete the given book.
     */
    public function delete(User $user, Book $book)
    {
        return $user->id === $book->user_id;
    }
}
