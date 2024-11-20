<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\JsonResponseTrait;

class Handler extends ExceptionHandler
{
    // Let call our json response trait
    use JsonResponseTrait;
    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {

            return $this->jsonResponse(401, "Access denied. Please log in to continue.", null);
            
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
