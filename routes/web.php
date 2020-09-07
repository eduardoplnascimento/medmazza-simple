<?php

Route::get('/', 'HomeController@index')->name('index');
Route::get('/signin', 'HomeController@signin')->name('login');
Route::post('/signin', 'AuthController@signin')->name('signin');
Route::get('/logout', 'AuthController@logout')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('users', 'UserController')->only(['update', 'edit']);
    Route::get('/appointments/load-all', 'AppointmentController@loadAll')->name('appointments.load-all');
    Route::get('/appointments/confirm/{id}', 'AppointmentController@confirm')->name('appointments.confirm');
    Route::get('/appointments/cancel/{id}', 'AppointmentController@cancel')->name('appointments.cancel');
    Route::resource('appointments', 'AppointmentController');
    Route::get('/doctors/available', 'DoctorController@getAvailableByDate')->name('doctors.available');
    Route::resource('doctors', 'DoctorController');
    Route::get('/patients/available', 'PatientController@getAvailableByDate')->name('patients.available');
    Route::resource('patients', 'PatientController');
    Route::resource('admins', 'UserController')->except(['update', 'edit']);
});
