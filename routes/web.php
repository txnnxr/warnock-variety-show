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
Route::get('shows/{show}/invite/respond/{key}', 'InvitationController@respond');
Route::post('/invite/respond/{invite}', 'InvitationController@registerResponse');
Route::get('/invite/{invite}/thank-you', 'InvitationController@guestThankYou');
Route::post('/invites/{invite}/generate-ics', 'InvitationController@generateICS');
Route::get('/invites/{invite}/edit', 'InvitationController@edit');
Route::post('/invites/{invite}/mark-as-opened', 'InvitationController@markAsOpened');

Route::post('deploy', 'DeployController@deploy');

require __DIR__.'/auth.php';
