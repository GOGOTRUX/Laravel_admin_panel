<?php


/*
|--------------------------------------------------------------------------
| Price Management Routes
|--------------------------------------------------------------------------
|
| Here is where you can add create factor, create logic admin routes.
|
*/

Route::post('/createfactor', ['uses'  => 'ManagePriceController@createFactor','as'=> 'createfactor']);
Route::get('/manage_price', ['uses'  => 'ManagePriceController@index','as'=> 'manage_price']);