<?php

use App\Http\Controllers\FilePondController;

Route::post('filepond/upload', [FilePondController::class, 'store']);
Route::patch('filepond/upload', [FilePondController::class, 'update']);
