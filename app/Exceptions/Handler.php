<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Log;
use App\Traits\JsonResponseTrait;
use Throwable;

class Handler extends ExceptionHandler
{

    // Let call our json response trait
    use JsonResponseTrait;

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle unauthorized actions
        if ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {

            Log::error('AuthorizationException:', ['exception' => $exception]);

            // If the request expects JSON (API call)
            if ($request->expectsJson()) {
                // Return a JSON response
                return $this->jsonResponse(403, "You are not authorized to perform this action.", null);
            }

            // Let redirect user back redirect back with a warning message if it's a web request
            return redirect()->back()->with('warning', 'You are not authorized to perform this action.');
        }
 
        // Handle not found exceptions 
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            // Log the exception for debugging
            Log::error('NotFoundHttpException:', ['exception' => $exception]);

            // If the request expects JSON (API call)
            if ($request->expectsJson()) {
                return $this->jsonResponse(404, "The requested resource was not found.", null);
            }

            // For web requests, show a custom message or redirect
            return redirect()->route('books.index')->with('error', 'The requested resource was not found.');
        }
        
        // Handle AuthenticationException (Unauthenticated)
        if ($exception instanceof AuthenticationException) {
            Log::error('AuthenticationException:', ['exception' => $exception]);

            if ($request->expectsJson()) {
                return $this->jsonResponse(401, "Please log in to continue.", null);
            }

            return redirect()->route('login')->with('error', 'You are not authenticated. Please log in.');
        }

        // Log others exceptions for debugging
        Log::error('Unhandled exception:', ['exception' => $exception]);

        return parent::render($request, $exception);
    }
}

