<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
{
    $user = Auth::user();

    // Check if user is authenticated, has the correct role, and is active
    if (!$user || $user->role !== $role || $user->status !== 'active') {
        return response()->json(['status' => false, 'message' => "Permission Denied!"], 403);
    }

    return $next($request);
}
}
