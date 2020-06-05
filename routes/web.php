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

/**
 * Swagger documentation is not available for production environment
 */
if (!in_array(app()->environment(), ['production'])) {
    Route::get('/', function () {
        return redirect('/docs');
    });

    Route::get('/docs', function () {
        return view('swagger');
    });
}

Route::group(['middleware' => 'auth'], function () {
    Route::post('/phone/validate', 'PhoneController@validatePhone');
    Route::post('/phone/check', 'PhoneController@checkPhone');
    Route::post('/border/checkpoint', 'BorderController@createCheckpoint');
    Route::get('/border/checkpoint', 'BorderController@getCheckpointList');
    Route::get('/border/checkpoint/{id}', 'BorderController@getCheckpoint');
    Route::put('/border/checkpoint/{id}', 'BorderController@updateCheckpoint');
    Route::delete('/border/checkpoint/{id}', 'BorderController@deleteCheckpoint');
    Route::post('/declaration', 'DeclarationController@createDeclaration');
    Route::get('/declaration', 'DeclarationController@getDeclarationList');
    Route::get('/declaration/{declarationCode}/signature', 'DeclarationController@getDeclarationSignature');
    Route::get('/declaration/{declarationCode}', 'DeclarationController@getDeclaration');
    Route::put('/declaration/{declarationCode}/dsp', 'DeclarationController@updateDeclaration');
    Route::get('/declaration/cnp/{cnp}', 'DeclarationController@getDeclarationByCnp');
    Route::get('/declaration/view/{declarationCode}', 'DeclarationController@viewDeclaration');
    Route::get('/declaration/search/{code}', 'DeclarationController@searchDeclaration');

    /** Siruta */
    Route::get('/siruta/county', 'SirutaController@getCountyList');
    Route::get('/siruta/city/{countyId}', 'SirutaController@getCityList');
    Route::get('/siruta/settlement/{countyId}', 'SirutaController@getSettlementList');
});
