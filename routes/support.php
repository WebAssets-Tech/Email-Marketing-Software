<?php

use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\SupportTicketReplyController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard/ticket', 'middleware' => ['auth', 'email.verified', 'installed', 'can:Everyone']], function () {

    // SupportTicketsController
    Route::get('/', [SupportTicketController::class, 'index'])
        ->name('support.ticket.new');

    Route::get('/unread', [SupportTicketController::class, 'unread'])
        ->name('support.ticket.unread');

    Route::get('/sent', [SupportTicketController::class, 'sent_reply'])
        ->name('support.ticket.sent.reply');

    Route::get('/starred', [SupportTicketController::class, 'starred'])
        ->name('support.ticket.sent.starred');

    Route::get('/solved', [SupportTicketController::class, 'solvedTickets'])
        ->name('support.ticket.solved');

    Route::get('/mark/as/solved/{ticket_no}', [SupportTicketController::class, 'mark_as_solved'])
        ->name('support.ticket.mark_as_solved')
        ->middleware('assigned.ticket');

    Route::get('/read/{ticket_no}', [SupportTicketController::class, 'show'])
        ->name('support.ticket.show')
        ->middleware('assigned.ticket');

    Route::post('/mark/star/{ticket_no}', [SupportTicketController::class, 'mark_star'])
        ->name('support.ticket.mark.star');

    Route::get('/download/attachment/{ticket_no}', [SupportTicketController::class, 'download_attachment'])
        ->name('support.ticket.download.attachment')
        ->middleware('assigned.ticket');

    Route::get('/search', [SupportTicketController::class, 'ticket_search'])
        ->name('support.ticket.search');

    // SupportTicketReplyController
    Route::post('/reply/{id}/{ticket_no}', [SupportTicketReplyController::class, 'index'])
        ->name('support.ticket.reply');

    Route::get('/submit-request', [SupportTicketController::class, 'submit_request'])
        ->name('submit.request');

    Route::post('/submit-request/submit', [SupportTicketController::class, 'submit_request_submit'])
        ->name('submit.request.submit');

    Route::get('/submit-request/success', [SupportTicketController::class, 'submit_request_success'])
        ->name('submit.request.success');

    Route::get('/ticket', [SupportTicketController::class, 'ticket'])
        ->name('ticket');

    Route::get('/ticket/open', [SupportTicketController::class, 'ticket_open'])
        ->name('ticket.open');

    Route::get('/ticket/answered', [SupportTicketController::class, 'ticket_answered'])
        ->name('ticket.answered');

    Route::get('/ticket/solved', [SupportTicketController::class, 'ticket_solved'])
        ->name('ticket.solved');

    Route::get('/ticket-reply/{ticket_no}', [SupportTicketController::class, 'ticket_reply'])
        ->name('ticket.reply');

});
