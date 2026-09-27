<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/mark-as-read', function () {
    $id = auth()->user()->id;
    DB::table('user_notifications')
        ->where('owner_id', $id)
        ->where('is_read', false)
        ->update(['is_read' => true, 'updated_at' => now()]);

    return back()->with('success', 'All notification marked as read');
})->name('markRead.notifications');
/**
 * EMAIL VERIFICATION
 */
Route::group(['middleware' => ['auth', 'installed']], function () {
    Route::get('email/verification/user', [AuthController::class, 'emailVerificationWithCode'])->name('email.verification.with.code'); // email verification
    Route::get('email/verification/code', [AuthController::class, 'emailVerificationCode'])->name('email.verification.code'); // email verification code send to email
    Route::get('email/verification/code/match', [AuthController::class, 'emailVerificationMatch'])->name('email.verification.code.match'); // email verification code match
});

Route::any('send/new/password', [AuthController::class, 'generateNewPassword'])->name('send.new.password')->middleware('installed'); // generate new password

/**
 * REGISTRATION
 */
Route::any('/user/register', [RegisterController::class, 'user_register'])->name('user_register')->middleware('installed');
Auth::routes();

/**
 * AUTH
 */
Route::group(['middleware' => ['auth', 'email.verified', 'installed', 'check.plan', 'saas.user.restriction']], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    // Route::get('logout', [AuthController::class, 'logout'])
    //     ->name('logout');
    /**
     * Language
     */
    Route::get('language', [LanguageController::class, 'langIndex'])
        ->middleware('can:Admin')
        ->name('language.index');
    Route::get('language/new', [LanguageController::class, 'langNew'])
        ->middleware('can:Admin')
        ->name('language.new');
    Route::any('language/store', [LanguageController::class, 'langStore'])
        ->middleware('can:Admin')
        ->name('language.store');
    Route::get('language/destroy/{id}', [LanguageController::class, 'langDestroy'])
        ->middleware('can:Admin')
        ->name('language.destroy');
    Route::get('language/translate/{id}', [LanguageController::class, 'translate_create'])
        ->middleware('can:Admin')
        ->name('language.translate');
    Route::any('language/translate/store', [LanguageController::class, 'translate_store'])
        ->middleware('can:Admin')
        ->name('language.translate.store');
    Route::any('language/change', [LanguageController::class, 'languagesChange'])
        ->name('language.change');
    Route::get('language/default/{id}', [LanguageController::class, 'defaultLanguage'])
        ->name('language.default');

    /**
     * PROFILE
     */
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::get('/change-password', [ProfileController::class, 'change_password'])
        ->name('profile.change.password')->middleware('password.confirm');
    Route::any('/password-changed', [ProfileController::class, 'password_changed'])
        ->name('profile.password.changed');
    Route::any('/user/update', [UserController::class, 'update'])
        ->name('user.update');
    Route::any('/user/personal/update', [UserController::class, 'personal_update'])
        ->name('user.personal.update');
    Route::post('/user/update/time-zone', [UserController::class, 'updateTimeZone'])
        ->name('updateTimeZone');

    /**
     * Organization
     */
    Route::get('/organization', [OrganizationSetupController::class, 'index'])
        ->middleware('can:Admin')
        ->name('org.index');
    Route::any('/organization/setup', [OrganizationSetupController::class, 'setup'])
        ->middleware('can:Admin')
        ->name('org.setup');

    /**
     * Page
     */
    Route::resource('/dashboard/page', PageController::class)->except('show')->middleware('can:Admin');
    /**
     * Contact Us
     */
    Route::get('dashboard/contact', [ContactController::class, 'index'])->name('contact.index')->middleware('can:Admin');
    Route::delete('dashboard/contact/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy')->middleware('can:Admin');
    Route::get('/contact/replay/{contact}', [ContactController::class, 'replay'])->name('contact.replay')->middleware('can:Admin');
    Route::get('/contact/show/{contact}', [ContactController::class, 'show'])->name('contact.show')->middleware('can:Admin');
    Route::get('/contact/replay/{contact}/sent', [ContactController::class, 'replaySent'])->name('contact.replay.sent')->middleware('can:Admin');

    /**
     * seo
     */
    Route::get('/seo', [SeoController::class, 'index'])
        ->middleware('can:Admin')
        ->name('seo.index');
    Route::any('/seo/setup', [SeoController::class, 'setup'])
        ->middleware('can:Admin')
        ->name('seo.setup');

    /**
     * SMTP
     */
    Route::get('/smtp', [SmtpController::class, 'index'])
        ->name('smtp.index');
    Route::get('/smtp/configure/{mail}', [SmtpController::class, 'configure'])
        ->name('smtp.configure');

    Route::any('/smtp/configure/store', [SmtpController::class, 'store'])
        ->middleware(['saas.expiry'])
        ->name('smtp.configure.store');
    Route::any('/smtp/configure/update/{mail}', [SmtpController::class, 'update'])
        ->name('smtp.configure.update');
    Route::get('/smtp/configure/remove/{mail}', [SmtpController::class, 'destroy'])->middleware('can:Admin')
        ->name('smtp.configure.destroy');
    Route::get('/smtp/configure/{mail}/set-default', [SmtpController::class, 'set_default'])
        ->name('smtp.configure.default');
    Route::get('/smtp/test-connection/{id}', [SmtpController::class, 'test'])
        ->name('smtp.connection.test');

    /**
     * System SMTP
     */
    Route::get('/system/smtp/setup/{mail}', [SmtpController::class, 'setAsSystemSmtp'])
        ->middleware('can:Admin')
        ->name('system.smtp.setup');

    // version 3.0

    Route::get('/system/smtp/configure', [SmtpController::class, 'systemSmtpConfigure'])
        ->middleware('can:Admin')
        ->name('system.smtp.configure');

    Route::get('/system/smtp/configure/update', [SmtpController::class, 'systemSmtpConfigureUpdate'])
        ->middleware('can:Admin')
        ->name('system.smtp.configure.update');

    Route::get('/system/smtp/configure/test', [SmtpController::class, 'systemSmtpConfigureTest'])
        ->middleware('can:Admin')
        ->name('system.smtp.configure.test');

    // version 3.0::END

    /**
     * Payment Setup
     */
    Route::get('/payment-setup/paypal', [PaymentSetupController::class, 'paypal'])
        ->middleware('can:Admin')
        ->name('payment.setup.paypal');
    Route::get('/payment-setup/paypal/create', [PaymentSetupController::class, 'paypalCreate'])
        ->middleware('can:Admin')
        ->name('payment.setup.paypal.create');

    Route::get('/payment-setup/paypal/disable', [PaymentSetupController::class, 'paypalDisable'])
        ->middleware('can:Admin')
        ->name('payment.setup.paypal.disable');

    Route::get('/payment-setup/stripe', [PaymentSetupController::class, 'stripe'])
        ->middleware('can:Admin')
        ->name('payment.setup.stripe');

    Route::get('/payment-setup/stripe/create', [PaymentSetupController::class, 'stripeCreate'])
        ->middleware('can:Admin')
        ->name('payment.setup.stripe.create');
    Route::get('/payment-setup/stripe/disable', [PaymentSetupController::class, 'stripeEnableDisable'])
        ->middleware('can:Admin')
        ->name('payment.setup.stripe.enabledisable');

    Route::get('/payment-setup/khalti', [PaymentSetupController::class, 'khalti'])
        ->middleware('can:Admin')
        ->name('payment.setup.khalti');
    Route::get('/payment-setup/khalti/create', [PaymentSetupController::class, 'khaltiCreate'])
        ->middleware('can:Admin')
        ->name('payment.setup.khalti.create');
    Route::get('/payment-setup/khalti/disable', [PaymentSetupController::class, 'khaltiEnableDisable'])
        ->middleware('can:Admin')
        ->name('payment.setup.khalti.enabledisable');

    /**
     * PURCHASED PLANS
     */
    Route::get('/purchased/plans', [PurchasedPlanController::class, 'index'])
        ->name('purchased.plan');

    Route::get('/download/invoice/{invoice}', [PurchasedPlanController::class, 'downloadInvoice'])
        ->name('download.invoice');

    /**
     * SERVER STATUS
     */
    Route::get('server/status', [ServerStatusController::class, 'index'])->name('server.status')
        ->middleware('can:Admin');

    // version 1.3

    /**
     * help
     */
    Route::get('help', function () {
        return view('help.index');
    })->name('help')->middleware('can:Admin');

    /**
     * CHAT PROVIDER
     */
    Route::get('chat/provider', [ChatProviderController::class, 'index'])
        ->name('chat.provider')
        ->middleware('can:Admin');

    Route::post('chat/provider/store', [ChatProviderController::class, 'store'])
        ->name('chat.store')
        ->middleware('can:Admin');

    Route::get('chat/provider/{id}/active', [ChatProviderController::class, 'activenow'])
        ->name('chat.active')
        ->middleware('can:Admin');

    Route::get('chat/provider/{id}/edit', [ChatProviderController::class, 'edit'])
        ->name('chat.edit')
        ->middleware('can:Admin');

    Route::post('chat/provider/{id}/update', [ChatProviderController::class, 'update'])
        ->name('chat.update')
        ->middleware('can:Admin');

    Route::get('chat/provider/{id}/delete', [ChatProviderController::class, 'destroy'])
        ->name('chat.destroy')
        ->middleware('can:Admin');

    // version 1.3::END

    /**
     * VERSION 2.2
     */
    Route::get('app/key', [ApiKeyController::class, 'index'])
        ->name('app.api.index');

    Route::post('app/key/store', [ApiKeyController::class, 'store'])
        ->name('app.api.store');
    /**
     * VERSION 2.2::END
     */

    //END
});

Route::get('test', function () {
    dd(date_default_timezone_get());
});
/**
 * Version 6.3.1
 */

//  ChatGPT version
Route::get('/chatgpt/chat', [ChatGPTController::class, 'chatgpt'])->name('chat.gpt.index');
Route::get('/chatgpt/chat/{parent_id}', [ChatGPTController::class, 'single'])->name('chat.gpt.single');
Route::post('/chatgpt/chat', [ChatGPTController::class, 'chat'])->name('chat.gpt.chat');
Route::post('/chatgpt/chat/store', [ChatGPTController::class, 'chatStore'])->name('chat.gpt.chat.store');
Route::post('/chatgpt/chat/store/chat', [ChatGPTController::class, 'chatFloating'])->name('chat.gpt.chat.store.floating');
Route::get('/chatgpt/setup', [ChatGPTController::class, 'chatgptSetup'])->name('chat.gpt.setup');
Route::get('/chatgpt/setup/update', [ChatGPTController::class, 'chatgptSetupUpdate'])->name('chat.gpt.setup.update');
