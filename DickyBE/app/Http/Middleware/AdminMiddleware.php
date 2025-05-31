<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has admin role
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // For web requests, redirect to login or show 403 page
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized. Admin access only.'], 403);
        }

        // Redirect non-admin users to home with error message
        return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
    }
}