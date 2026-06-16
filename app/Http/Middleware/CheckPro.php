<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPro
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isPro()) {
            return redirect()->route('subscription.index')
                ->with('warning', 'This feature requires a Pro subscription.');
        }

        return $next($request);
    }
}