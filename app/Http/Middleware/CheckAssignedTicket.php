<?php

namespace App\Http\Middleware;

use App\Models\SupportTicket;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckAssignedTicket {
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (Auth::user()->user_type == 'Admin') {
            return $next($request);
        } else {

            $check = SupportTicket::where('ticket_no', $request->route('ticket_no'))
                ->with(['assigned_role' => function ($query) {
                    $query->where('user_id', Auth::id());
                }])
                ->first();

            if ($check->assigned_role != null) {
                return $next($request);
            } else {
                return redirect()->route('support.ticket.new')->withErrors(['You are not allowed to access this ticket']);
            }
        }
    }
}
