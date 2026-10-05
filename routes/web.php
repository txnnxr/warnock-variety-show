<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\SubmissionApplicationController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $show = \App\Models\Show::where('date', '>=', Carbon::now())->orderBy('date', 'asc')->first();
    return view('home', compact('show'));
});
Route::get('/card', [CardController::class, 'show'])->name('card.show');
Route::get('/shows/{show}/view', [ShowController::class, 'show'])->name('shows.show');
Route::get('/shows/{show}/invite/respond/{key}', [InviteController::class, 'respond']);
Route::post('/shows/{show}/invite/respond/{key}', [InviteController::class, 'registerResponse']);
Route::get('/invite/{invite}/thank-you', [InviteController::class, 'guestThankYou']);
Route::post('/invites/{invite}/generate-ics', [InviteController::class, 'generateICS']);
Route::get('/invites/{invite}/edit', [InviteController::class, 'edit']);
Route::post('/invites/{invite}/mark-as-sent', [InviteController::class, 'markAsSent']);
Route::post('/invites/{invite}/mark-as-opened', [InviteController::class, 'markAsOpened']);

Route::get('/shows/{show}/submission-applications/create', [SubmissionApplicationController::class, 'create']);
Route::post('/shows/{show}/submission-applications/', [SubmissionApplicationController::class, 'store']);
Route::get('/shows/{show}/submission-applications/{submissionApplication}/view', [SubmissionApplicationController::class, 'show']);

Route::get('/shows/{show}/invite/guest-request', [InviteController::class, 'guestRequest'])->name('invites.guest-request');
Route::post('/shows/{show}/invite/guest-request', [InviteController::class, 'guestRequestSave']);

require __DIR__.'/auth.php';
