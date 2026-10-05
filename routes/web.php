<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\LineupController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\ShowEmailController;
use App\Http\Controllers\ShowPhotoController;
use App\Http\Controllers\SubmissionApplicationController;
use App\Models\Show;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $show = Show::upcoming()->where('canceled', false)->orderBy('date', 'asc')->first();
    return view('home', compact('show'));
})->name('home');
Route::get('/card', [CardController::class, 'show'])->name('card.show');
Route::get('/shows/archive', [ArchiveController::class, 'index'])->name('shows.archive');
Route::get('/shows/{show}/view', [ShowController::class, 'show'])->name('shows.show');

// Guests reach their invite only through its secret key, never its ID.
Route::get('/shows/{show}/invite/respond/{key}', [InviteController::class, 'respond'])->name('invites.respond');
Route::post('/shows/{show}/invite/respond/{key}', [InviteController::class, 'registerResponse'])->middleware('throttle:public-forms');
Route::get('/invites/{invite:key}/thank-you', [InviteController::class, 'guestThankYou'])->name('invites.thank-you');
Route::get('/invites/{invite:key}/calendar', [InviteController::class, 'calendar'])->name('invites.calendar');
Route::post('/invites/{invite:key}/mark-as-opened', [InviteController::class, 'markAsOpened'])->name('invites.mark-as-opened');

Route::get('/shows/{show}/invite/guest-request', [InviteController::class, 'guestRequest'])->name('invites.guest-request');
Route::post('/shows/{show}/invite/guest-request', [InviteController::class, 'guestRequestSave'])->middleware('throttle:public-forms');
Route::get('/join/{key}', [InviteController::class, 'join'])->name('invites.join');

Route::get('/shows/{show}/submission-applications/create', [SubmissionApplicationController::class, 'create']);
Route::post('/shows/{show}/submission-applications', [SubmissionApplicationController::class, 'store'])->middleware('throttle:public-forms');
Route::get('/applications/{submissionApplication:key}', [SubmissionApplicationController::class, 'status'])->name('applications.status');

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('shows', [ShowController::class, 'index'])->name('shows.index');
    Route::get('shows/create', [ShowController::class, 'create'])->name('shows.create');
    Route::post('shows', [ShowController::class, 'store'])->name('shows.store');
    Route::get('shows/{show}/edit', [ShowController::class, 'edit'])->name('shows.edit');
    Route::put('shows/{show}', [ShowController::class, 'update'])->name('shows.update');
    Route::delete('shows/{show}', [ShowController::class, 'destroy'])->name('shows.destroy');
    Route::post('shows/{show}/cancel', [ShowController::class, 'cancel'])->name('shows.cancel');
    Route::post('shows/{show}/restore', [ShowController::class, 'restore'])->name('shows.restore');

    Route::get('shows/{show}/invite', [InviteController::class, 'index'])->name('invites.index');
    Route::post('shows/{show}/invite', [InviteController::class, 'store']);
    Route::post('shows/{show}/invite/send-all', [InviteController::class, 'sendAll'])->name('invites.send-all');
    Route::post('shows/{show}/invite/past-guests', [InviteController::class, 'invitePastGuests'])->name('invites.past-guests');
    Route::post('shows/{show}/invite/reset-guest-link', [InviteController::class, 'resetGuestLink'])->name('invites.reset-guest-link');
    Route::post('/invites/{invite}/send', [InviteController::class, 'send'])->name('invites.send');
    Route::get('/invites/{invite}/edit', [InviteController::class, 'edit'])->name('invites.edit');
    Route::put('/invites/{invite}', [InviteController::class, 'update'])->name('invites.update');
    Route::delete('/invites/{invite}', [InviteController::class, 'destroy'])->name('invites.destroy');
    Route::post('/invites/{invite}/mark-as-sent', [InviteController::class, 'markAsSent']);
    Route::post('/invites/{invite}/guest-request/approve', [InviteController::class, 'guestRequestApprove'])->name('invites.guest-request.approve');

    Route::get('shows/{show}/submission-applications', [SubmissionApplicationController::class, 'index']);
    Route::get('/shows/{show}/submission-applications/{submissionApplication}/view', [SubmissionApplicationController::class, 'show']);
    Route::post('/submission-applications/{submissionApplication}/approve', [SubmissionApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/submission-applications/{submissionApplication}/deny', [SubmissionApplicationController::class, 'deny'])->name('applications.deny');

    Route::get('shows/{show}/lineup', [LineupController::class, 'index'])->name('lineup.index');
    Route::post('/lineup/{exhibitor}/up', [LineupController::class, 'moveUp'])->name('lineup.up');
    Route::post('/lineup/{exhibitor}/down', [LineupController::class, 'moveDown'])->name('lineup.down');
    Route::post('shows/{show}/lineup/announce', [ShowEmailController::class, 'announceLineup'])->name('shows.announce-lineup');
    Route::post('shows/{show}/recap', [ShowEmailController::class, 'sendRecap'])->name('shows.send-recap');

    Route::post('shows/{show}/photos', [ShowPhotoController::class, 'store'])->name('photos.store');
    Route::delete('/photos/{photo}', [ShowPhotoController::class, 'destroy'])->name('photos.destroy');

    Route::get('people', [PersonController::class, 'index'])->name('people.index');
    Route::get('people/{person}', [PersonController::class, 'show'])->name('people.show');
    Route::post('people/{person}/merge', [PersonController::class, 'merge'])->name('people.merge');
});

require __DIR__.'/auth.php';
