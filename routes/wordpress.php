<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'email.verified', 'installed', 'saas.user.restriction']], function () {
    Route::get('/dashboard/wordpress', [WordPressController::class, 'index'])->name('wordpress.index');
    Route::post('/dashboard/wordpress/store', [WordPressController::class, 'store'])->name('wordpress.store');
    Route::post('/dashboard/wordpress/token', [WordPressController::class, 'generate_token'])->name('wordpress.generate.token');

});
