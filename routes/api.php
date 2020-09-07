<?php

Route::post('auth', 'AuthController@makeAppLogin');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('doctors', 'API\DoctorController');
});
