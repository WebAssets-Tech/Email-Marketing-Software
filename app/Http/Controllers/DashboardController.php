<?php

namespace App\Http\Controllers;

class DashboardController extends Controller {
    public function dashboard() {
        // dd('check');
        return view('dashboard.dashboard');
    }
    //END
}
