<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ShowController;
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

Route::get('/', function () {
    return view('home');
});


Route::resource('mailing-list', 'MailingListController');
Route::resource('rsvps', 'RSVPController');
Route::resource('shows', 'ShowController');
Route::get('shows/{show}/invite', 'InviteController@index');
Route::post('shows/{show}/invite', 'InviteController@store');
Route::get('shows/{show}/invite/respond/{key}', 'InviteController@respond');
Route::post('shows/{show}/invite/respond/{key}', 'InviteController@registerResponse');
