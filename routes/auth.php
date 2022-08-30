<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('home');
    });

//    Route::get('register', [RegisteredUserController::class, 'create'])
//                ->name('register');

//    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

//    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//                ->name('password.request');
//
//    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//                ->name('password.email');

//    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//                ->name('password.reset');
//
//    Route::post('reset-password', [NewPasswordController::class, 'store'])
//                ->name('password.update');

    Route::get('/shows/{show}/invite/guest-request', 'InviteController@guestRequest')->name('invites.guest-request');
    Route::post('/shows/{show}/invite/guest-request', 'InviteController@guestRequestSave');

    Route::get('/card', 'CardController@show')->name('card.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

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

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::resource('mailing-list', 'MailingListController');
    Route::resource('rsvps', 'RSVPController');
    Route::get('shows', 'ShowController@index');
    Route::get('shows/create', 'ShowController@create');
    Route::post('shows', 'ShowController@store');
    Route::get('shows/{show}/edit', 'ShowController@edit');
    Route::put('shows/{show}', 'ShowController@update');
    Route::delete('shows/{show}', 'ShowController@destroy');

    Route::get('shows/{show}/invite', 'InviteController@index');
    Route::post('/invite/{invite}/send', 'InviteController@send');
    Route::post('shows/{show}/toggle-public-rsvp', 'ShowController@togglePublicRsvp');
    Route::get('shows/{show}/invite', 'InviteController@index')->name('admin.show');
    Route::post('shows/{show}/invite', 'InviteController@store');
    Route::post('/invites/{invite}/guest-request/approve', 'InviteController@guestRequestApprove')->name('invites.guest-request.approve');
});
