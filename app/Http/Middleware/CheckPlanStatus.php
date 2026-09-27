<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\EmailSMSLimitRate;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->user_type != 'Admin' && 
        !EmailSMSLimitRate::UserCheck()->first()?->status) {
            Alert::warning('Unauthorized!', 'You don\'t have any active plan');
            return redirect('/#pricing');
        }
        
        return $next($request);
    }
}
