<?php

use App\Http\Controllers\SMTP\MailgunController;
use Illuminate\Support\Facades\Route;

Route::prefix('mailgun')->name('mailgun.')->group(function () {
    Route::get('domain/create', [MailgunController::class, 'create'])->name('domain.create');
    Route::post('domain', [MailgunController::class, 'store'])->name('domain.store');
    Route::get('domain/index', [MailgunController::class, 'index'])->name('domain.index');
    Route::get('domain/show/{domain}', [MailgunController::class, 'show'])->name('domain.show');
    Route::get('domain/verify/{domain}', [MailgunController::class, 'verify'])->name('domain.verify');
    Route::get('domain/delete/{domain}', [MailgunController::class, 'delete'])->name('domain.destroy');
});
