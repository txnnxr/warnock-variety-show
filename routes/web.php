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



Route::get('shows/{show}/invite/respond/{key}', 'InviteController@respond');
Route::post('shows/{show}/invite/respond/{key}', 'InviteController@registerResponse');
Route::get('/invite/{invite}/thank-you', 'InviteController@guestThankYou');

require __DIR__.'/auth.php';
