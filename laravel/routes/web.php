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

Route::get('/test', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('test', [
        'name' => request('name')
    ]);
});

Route::get('posts/{post}', 'PostsController@show');

Route::get('/documents', 'DocumentsController@index')->name('documents.index');
Route::get('/documents/{document}', 'DocumentsController@show')->name('documents.show');
Route::post('/documents', 'DocumentsController@create')->name('documents.create');
Route::delete('/documents/{document}', 'DocumentsController@destroy')->name('documents.destroy');
