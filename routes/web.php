<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('shows/{show}/view', 'ShowController@show')->name('shows.show');
Route::get('shows/{show}/invite/respond/{key}', 'InviteController@respond');
Route::post('shows/{show}/invite/respond/{key}', 'InviteController@registerResponse');
Route::get('/invite/{invite}/thank-you', 'InviteController@guestThankYou');
Route::post('/invites/{invite}/generate-ics', 'InviteController@generateICS');
Route::get('/invites/{invite}/edit', 'InviteController@edit');
Route::post('/invites/{invite}/mark-as-sent', 'InviteController@markAsSent');
Route::post('/invites/{invite}/mark-as-opened', 'InviteController@markAsOpened');

Route::get('shows/{show}/submission-applications/create', 'SubmissionApplicationController@create');
Route::post('shows/{show}/submission-applications/', 'SubmissionApplicationController@store');

require __DIR__.'/auth.php';
