<?php

use Illuminate\Support\Facades\Auth;
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

//auth
Auth::routes(['verify' => true]);

//about
Route::get('/about', function() {
    return view('about');
})->name('about');

//home
Route::get('/', function() {
    return redirect('/home');
});
Route::get('/home', 'ListController@index')->name('home');

//create task
Route::get('/task/create', 'TaskController@createTask')->name('task/create');

//watch task
Route::get('/task', 'TaskController@index')->name('task');

//modifying task
Route::post('/task/change', 'TaskController@changeTask')->name('task/change');
Route::post('/task/next_status', 'TaskController@nextStatus')->name('task/next_status');
Route::post('/task/delete', 'TaskController@delete')->name('task/delete');
