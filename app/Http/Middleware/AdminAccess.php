<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $roleCurrentUser = Auth::user()->role;
        //1 = admin
        //2= Normal User
        if ($roleCurrentUser !== 1) {
            return response()->json(
                [
                    'message' => "Go away from here!",
                    'status' => 401
                ],
                401
            );
        }

        return $next($request);
    }
}
