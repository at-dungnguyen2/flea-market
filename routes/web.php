<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categories', 'CategoryController');
Route::resource('posts', 'PostController');
Route::get('sidebarCategories', 'CategoryController@getSidebarCategories');

Auth::routes();

Route::get('/home', 'HomeController@index');


Route::get('image', function ($file) {
  $file = Storage::get('uploads/438.jpg');
  dd($file);
});
