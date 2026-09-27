<?php

use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'email.verified', 'installed', 'saas.user.restriction']], function () {
    Route::get('/agents', [AgentController::class, 'index'])
        ->name('agents.index');

    Route::post('/agent/store', [AgentController::class, 'store'])
        ->name('agent.store');

    Route::post('/agent/update/{id}', [AgentController::class, 'update'])
        ->name('agent.update');

    Route::get('/agent/remove/{id}', [AgentController::class, 'destroy'])
        ->name('agent.destroy');

    Route::get('/agent/restricted/{id}', [AgentController::class, 'restricted'])
        ->name('agent.restricted');

});
