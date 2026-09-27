<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'email.verified', 'installed', 'saas.user.restriction']], function () {
    Route::get('/dashboard/activecms', [ActiveCmsController::class, 'index'])->name('activecms.index');
    Route::post('/dashboard/activecms/store', [ActiveCmsController::class, 'store'])->name('activecms.store');
    Route::post('/dashboard/activecms/token', [ActiveCmsController::class, 'generate_token'])->name('activecms.generate.token');

    Route::get('/dashboard/activecms/fetch_data', [ActiveCmsController::class, 'fetch_data'])->name('activecms.fetch.data');
    Route::get('/dashboard/activecms/store_data', [ActiveCmsController::class, 'fetch_data_store'])->name('activecms.fetch.store');
});
