<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/docs');
});

Route::get('/docs', function () {
    return view('swagger');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/phone/validate', 'PhoneController@validatePhone');
    Route::post('/phone/check', 'PhoneController@checkPhone');
    Route::post('/user', 'UserController@createUser');
});
