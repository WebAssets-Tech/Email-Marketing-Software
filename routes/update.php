<?php

use App\Http\Controllers\AutoUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'admin', 'email.verified', 'installed']], function () {
    Route::get('/software/update', [AutoUpdateController::class, 'index'])
        ->name('auto.update.index');

    Route::get('/software/is-on-fire', [AutoUpdateController::class, 'update'])
        ->name('auto.update.fire');

    Route::post('/software/update-version/store', [AutoUpdateController::class, 'update'])
        ->name('auto.update.store');

    Route::post('/software/update-uploader', [AutoUpdateController::class, 'fileUpload'])
        ->name('zip.uploader');

    Route::get('finalizing-update/', [AutoUpdateController::class, 'finalize']);
});
