<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'email.verified', 'installed', 'saas.user.restriction']], function () {
    Route::get('/dashboard/perfex', [PerfexController::class, 'index'])->name('perfex.index');
    Route::post('/dashboard/perfex/store', [PerfexController::class, 'store'])->name('perfex.store');
    Route::post('/dashboard/perfex/token', [PerfexController::class, 'generate_token'])->name('perfex.generate.token');

    Route::get('/dashboard/perfex/fetch_data', [PerfexController::class, 'fetch_data'])->name('perfex.fetch.data');
    Route::get('/dashboard/perfex/store_data', [PerfexController::class, 'store_to_database'])->name('perfex.fetch.store');
});
