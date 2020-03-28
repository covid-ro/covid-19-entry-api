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
    Route::get('/border/checkpoint', 'BorderController@getCheckpointList');
    Route::get('/border/checkpoint/{id}', 'BorderController@getCheckpoint');
    Route::put('/border/checkpoint/{id}', 'BorderController@updateCheckpoint');
    Route::post('/declaration', 'DeclarationController@createDeclaration');
    Route::get('/declaration', 'DeclarationController@getDeclarationList');
    Route::get('/declaration/{declarationCode}', 'DeclarationController@getDeclaration');
});
