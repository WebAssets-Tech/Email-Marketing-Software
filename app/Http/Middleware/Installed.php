<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class Installed {
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        try {
            if (env('APP_INSTALL') == 'NO') {
                return redirect()->route('saasOrsubscription');
            }

            return $next($request);
        } catch (Exception $exception) {
            return redirect()->route('saasOrsubscription');
        }
    }
}
