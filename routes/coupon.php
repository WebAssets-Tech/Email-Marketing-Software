<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'email.verified', 'installed']], function () { // 'installed' is a middleware that is added in the AppServiceProvider
    // Coupon Setting
    Route::get('coupon', [CouponController::class, 'index'])->name('coupon.index')->middleware('can:Admin'); // Coupon Setting
    Route::get('coupon/create', [CouponController::class, 'create'])->name('coupon.create')->middleware('can:Admin'); // Coupon Create
    Route::post('coupon/store', [CouponController::class, 'store'])->name('coupon.store')->middleware('can:Admin'); // Coupon Store
    Route::get('coupon/delete/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy')->middleware('can:Admin'); // Coupon Delete
    Route::get('coupon/edit/{id}', [CouponController::class, 'edit'])->name('coupon.edit')->middleware('can:Admin'); // Coupon Edit
    Route::post('coupon/update/{id}', [CouponController::class, 'update'])->name('coupon.update')->middleware('can:Admin'); // Coupon Update
    Route::get('coupon/published', [CouponController::class, 'published'])->name('coupon.published')->middleware('can:Admin'); // Coupon Published
});

Route::get('apply-coupon', [CouponController::class, 'apply_coupon'])->name('coupon.apply'); // Apply Coupon
