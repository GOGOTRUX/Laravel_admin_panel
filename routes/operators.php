<?php


/*
|--------------------------------------------------------------------------
| Super Admin Routes Routes
|--------------------------------------------------------------------------
|
| Here is where you can add super admin routes.
|
*/
//OperatorsController
Route::resource('operators', 'OperatorsController');
Route::get('operators/{type}/operators', 'OperatorsController@index')->name('operators');
Route::post('operators/create', 'OperatorsController@create')->name('operators');
Route::post('operators/status','OperatorsController@updateStatus')->name('operators/status');
Route::post('getmobiledetails','OperatorsController@verifyMobile')->name('getmobiledetails');
Route::post('operatordelete','OperatorsController@destroy')->name('operatordelete');
Route::post('operatorBlocked','OperatorsController@operatorBlocked')->name('operatorBlocked');
Route::post('operator/unblock','OperatorsController@operatorUnlock')->name('unblocked');
Route::post('editbankinfo','OperatorsController@updateBankInfo')->name('editbankinfo');
Route::post('getdriverimage', 'OperatorsController@getdriverImage')->name('getdriverimage');
Route::post('operatorselectdelete','OperatorsController@deleteselected')->name('operatorselectdelete');

//DriverController
Route::resource('Driver', 'DriverController');
Route::post('/driver/update', 'DriverController@update')->name('driver.update');
Route::get('/update/driverinfo/{id}/','DriverController@edit')->name('update/driverinfo');
Route::get('/delete/driverinfo/{id}/','DriverController@destroy')->name('delete/driverinfo');
Route::post('/updatebusiness/','DriverController@updatebusiness')->name('updatebusiness');
Route::post('deletedriver','DriverController@destroy')->name('deletedriver');

//business information
Route::resource('business','BusinessController');

//vehicles information
Route::resource('vehicles','VehiclesController');
Route::post('getmodelname','VehiclesController@getmodelname')->name('getmodelname');

//upload additional documents
Route::resource('documents', 'DocumentController');
Route::post('save-documents','DocumentController@save')->name('save-documents');
Route::post('verify-doc','DocumentController@verify')->name('verify-doc');
Route::post('deletedocument','DocumentController@destroy')->name('deletedocument');
Route::get('update/documentinfo/{id}','DocumentController@edit')->name('update/documentinfo/');
Route::post('update/document','DocumentController@update')->name('update/document');

//verify operator bank details
Route::post('verify-op-bank-details','OperatorsController@verifyBankInfo')->name('verify-op-bank-details');
//operators vehicle controller
Route::resource('OperatorVehicles', 'OperatorVehiclesController');
Route::get('/update/Vehicles/{id}/{op_type}','OperatorVehiclesController@edit')->name('update/Vehicles');
Route::post('operatorvehicles/update','OperatorVehiclesController@businessUpdate')->name('operatorvehicles/update');
Route::post('vehicles/update', 'OperatorVehiclesController@update')->name('vehicles.update');
Route::post('deleteoperatorvehicle','OperatorVehiclesController@destroy')->name('deleteoperatorvehicle');

//book partner
Route::post('/book-partner', ['uses'  => 'OperatorsController@bookPartner','as'=> 'book-partner']);
Route::post('partner/delete','OperatorsController@destroy')->name('partner-delete');
Route::post('get-partner-rates','OperatorsController@partnerRates')->name('get-partner-rates');



