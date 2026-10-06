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
        $user = auth()->user();
        if ($user && $user->user_type != 'Admin') {
            $hasActivePlan = EmailSMSLimitRate::UserCheck()->where('status', 1)->exists();
            if (!$hasActivePlan) {
                // Automatically assign and activate Free Tier plan
                assignFreePlanToUser($user);
            }
        }
        
        return $next($request);
    }
}
