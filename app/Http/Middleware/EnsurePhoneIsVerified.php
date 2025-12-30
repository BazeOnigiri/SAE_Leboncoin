<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePhoneIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->phone_verified && !$request->routeIs('phone.verify.*')) {
            return redirect()->route('phone.verify.show');
        }

        return $next($request);
    }
}
