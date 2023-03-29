<?php


/*
|--------------------------------------------------------------------------
| Super Admin Routes Routes
|--------------------------------------------------------------------------
|
| Here is where you can add super admin routes.
|
*/
//Routing Vehiclefacilitymaster
Route::resource('vehiclemaster','VehiclefacilitymasterController');

//Routing VehicleFacilities
Route::get('Vehicles/edit/{id}','VehicleFacilitiesController@edit')->name('Vehicles/edit/');
Route::post('Vehiclesselectdeleted','VehicleFacilitiesController@deleteSelected')->name('Vehiclesselectdeleted');
Route::post('vehiclesdelete','VehicleFacilitiesController@destroy')->name('vehiclesdelete');
Route::resource('vehiclesfacility','VehicleFacilitiesController');
Route::post('vehiclesfacility','VehicleFacilitiesController@index')->name('vehiclesfacility');
Route::get('vehiclesfacility/edit/{id}','VehicleFacilitiesController@edit')->name('vehiclesfacility/edit/');
Route::post('vehiclesfacility/update','VehicleFacilitiesController@update')->name('vehiclesfacility/update');
Route::post('vehiclesfacility/create','VehicleFacilitiesController@create')->name('vehiclesfacility/create');
Route::post('vehiclesfacility/store','VehicleFacilitiesController@store')->name('vehiclesfacility/store');
Route::post('vehiclesfacilityone','VehicleFacilitiesController@softdelete')->name('vehiclesfacilityone');
Route::post('vehiclesfacilitydelete','VehicleFacilitiesController@deleteSelected')->name('vehiclesfacilitydelete');
Route::post('getcapacity','VehicleFacilitiesController@getcapacity')->name('getcapacity');