<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SeoController extends Controller {
    /**
     * setup
     */
    public function setup(Request $request) {
        if ($request->has('description')) {
            $system = Seo::where('name', 'description')->first();
            $system->name = 'description';
            $system->value = $request->description;
            $system->save();
        }

        if ($request->has('keywords')) {
            $system = Seo::where('name', 'keywords')->first();
            $system->name = 'keywords';
            $system->value = $request->keywords;
            $system->save();
        }

        if ($request->has('google_analytics')) {
            $system = Seo::where('name', 'google_analytics')->first();
            $system->name = 'google_analytics';
            $system->value = $request->google_analytics;
            $system->save();
        }

        notify()->success(translate('Updated'));

        return back();
    }

    /**
     * Organization
     */
    public function index() {
        return view('settings.seo.index');
    }

}
