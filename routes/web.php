<?php

Route::get('/', 'HomeController@index')->name('index');
Route::get('/signin', 'HomeController@signin')->name('login');
Route::get('/signup', 'HomeController@signup')->name('register');
Route::post('/signin', 'AuthController@signin')->name('signin');
Route::post('/signup', 'AuthController@signup')->name('signup');
Route::get('/logout', 'AuthController@logout')->name('logout');
