<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OrganizationSetup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TestController;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\ContactController;


Route::post('update-env', function (Request $request) {
    $system = OrganizationSetup::where('name', 'test_connection_email')->first();
    $system->name = 'test_connection_email';
    $system->value = $request->TEST_CONNECTION_MAIL;
    $system->save();
    setEnv([$request->except('_token', '_method')]);

    return back();
})->name('env.update');

Route::get('/debug-mode-on', function (Request $request) {
    overWriteEnvFile('APP_DEBUG', 'true');
});

Route::get('/debug-mode-off', function (Request $request) {
    overWriteEnvFile('APP_DEBUG', 'false');
});

Route::post('update-env', function (Request $request) {
    $system = OrganizationSetup::where('name', 'test_connection_email')->first();
    $system->name = 'test_connection_email';
    $system->value = $request->TEST_CONNECTION_MAIL;
    $system->save();
    setEnv([...$request->except('_token', '_method')]);

    return back();
})->name('env.update');

Route::get('/demo-mode-off', function (Request $request) {
    overWriteEnvFile('DEMO_MODE', 'NO');
});

Route::get('/demo-mode-on', function (Request $request) {
    overWriteEnvFile('DEMO_MODE', 'YES');
});

Route::get('/x', function (Request $request) {
    return listdirfile_by_date();
})->name('x');

// Contact Us Page
if (env('DISABLE_CONTACT_FORM') == 'NO') {
    Route::get('contact', [ContactController::class, 'create'])->name('contact.create'); //contact page
    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
}

Route::get('page/{slug}', [PageController::class, 'show'])->name('frontend.page.show'); // page slug

// TEST
Route::get('/test/csv/index', [TestController::class, 'index']);
Route::post('/test/csv/upload', [TestController::class, 'upload_csv_records'])->name('test.upload');
// TEST

Route::group(['middleware' => ['auth', 'email.verified']], function () {
    //
});

/**
 * IMPORTING EMAIL TEMPLATES
 *
 * This is raw SQL query
 */
Route::get('/import/templates', function () {

    // check user template imported
    $check_imported = User::where('id', Auth::user()->id)->first();

    if ($check_imported->imported == true) {
        Alert::info('Sorry', 'Templates already imported');

        return back();
    }

    $last_inserted_id = App\Models\TemplateBuilder::all()->last()->id ?? 0;
    $store_id = $last_inserted_id + 1;
    $store_next_id = $store_id + 1;
    $store_3rd = $store_next_id + 1;
    $store_4th = $store_3rd + 1;
    $store_5th = $store_4th + 1;
    $store_6th = $store_5th + 1;
    $store_7th = $store_6th + 1;
    $store_8th = $store_7th + 1;
    $store_9th = $store_8th + 1;

    $owner_id = Auth::user()->id;

    $user = User::where('id', Auth::id())->first();
    $user->imported = true;
    $user->save();

    Artisan::call('db:seed', ['--class' => 'TemplateBuilderSeeder']);
    return back();
})->name('import.template')->middleware(['saas.user.restriction', 'saas.expiry']);
