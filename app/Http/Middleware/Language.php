<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Language {
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        app()->setLocale(session('locale'));

        return $next($request);
    }
}
