<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use RealRashid\SweetAlert\Facades\Alert;

class AutoUpdateController extends Controller {
    public function index() {
        return view('auto_update.edit');
    }

    public function update(Request $request) {
        overWriteEnvFile('UPGRADING', 'YES');
        if (env('UPGRADING') !== 'YES') {
            overWriteEnvFile('UPGRADING', 'YES');
        }
        Artisan::call('schedule:run');
        notify()->success('Your application is updated', 'Hurray!');

        return back()->with('app-updated', 'Your application is updated');
    }

    public function finalize() {
        $package = file_get_contents(base_path('package.json'));
        $packageJson = json_decode($package);
        overWriteEnvFile('VERSION', $packageJson->version);
        Artisan::call('migrate');
        Artisan::call('storage:link');
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        notify()->success(__('Update Finished'), 'Hurray!');
        Alert::success('Success', __('Update Finished'));
        overWriteEnvFile('NEEDS_MIGRATION', false);

        return redirect(route('dashboard'));
    }
}
