<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRequestIsAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!is_null($request->headers->get('Authorization'))) {
            if ($request->headers->get('Authorization') === env('AUTH_KEY')) {
                return $next($request);
            }
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
