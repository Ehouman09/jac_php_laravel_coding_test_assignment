<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;


class VerifyCsrfTokenMiddleware extends Middleware
{
    /**
     * URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*', // This will exclude all API routes from CSRF protection
    ];
}