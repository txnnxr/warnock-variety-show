<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RSVPController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\SubmissionApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::resource('mailing-list', MailingListController::class);
    Route::resource('rsvps', RSVPController::class);
    Route::get('shows', [ShowController::class, 'index'])->name('shows.index');
    Route::get('shows/create', [ShowController::class, 'create'])->name('shows.create');
    Route::post('shows', [ShowController::class, 'store'])->name('shows.store');
    Route::get('shows/{show}/edit', [ShowController::class, 'edit'])->name('shows.edit');
    Route::put('shows/{show}', [ShowController::class, 'update'])->name('shows.update');
    Route::delete('shows/{show}', [ShowController::class, 'destroy'])->name('shows.destroy');

    Route::get('shows/{show}/invite', [InviteController::class, 'index']);
    Route::post('shows/{show}/invite', [InviteController::class, 'store']);
    Route::post('/invites/{invite}/guest-request/approve', [InviteController::class, 'guestRequestApprove'])->name('invites.guest-request.approve');

    Route::get('shows/{show}/submission-applications', [SubmissionApplicationController::class, 'index']);
    Route::get('/shows/{show}/submission-applications/{submissionApplication}/edit', [SubmissionApplicationController::class, 'edit']);
    Route::get('/submission-applications/{submissionApplication}/approve', [SubmissionApplicationController::class, 'approve']);
    Route::get('/submission-applications/{submissionApplication}/deny', [SubmissionApplicationController::class, 'deny']);
});
