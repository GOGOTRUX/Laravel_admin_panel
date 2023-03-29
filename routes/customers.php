<?php


/*
|--------------------------------------------------------------------------
| Super Admin Routes Routes
|--------------------------------------------------------------------------
|
| Here is where you can add super admin routes.
|
*/

Route::resource('customer','CustomerController');
Route::post('customer/blocked','CustomerController@customerBlocked')->name('customerBlocked');
Route::post('customer/unblock','CustomerController@customerUnblocked')->name('customer-unblocked');
Route::post('customer/verify','CustomerController@verifyCustomer')->name('verify-customer');
Route::post('customer/delete','CustomerController@destroy')->name('customer-delete');
Route::post('/book-customer', ['uses'  => 'CustomerController@bookCustomer','as'=> 'book-customer']);
