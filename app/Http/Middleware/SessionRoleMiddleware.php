<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // if (Session::get('role') === $role) {  
        $userRole = Session::get('role');
        // Check if the user's role matches any of the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request); 
        }else{
            // If unauthorized, redirect or abort with an error
            return redirect('/unauthorized'); // Customize this route
        }
    }
}
