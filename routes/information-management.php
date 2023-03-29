<?php


/*
|--------------------------------------------------------------------------
| Information Management Routes
|--------------------------------------------------------------------------
|
| Here is where you can driver katta,home screen,and send bulk notification,email,sms routes.
|
*/

/*
|-----------------------------------------
| operator information routes
|-----------------------------------------
*/

Route::resource('information','InformationController');
Route::resource('notification','NotificationController');

Route::resource('sms', 'SendSmsController');
Route::resource('mail','SendMailController');

Route::post('driver_katta', 'DriverHomeController@driverKatta')->name('driver_katta');
Route::post('gogotrux_mitr', 'DriverHomeController@gogotruxMitr')->name('gogotrux_mitr');
Route::resource('driverhome','DriverHomeController');
Route::post('delete-bannerimage','DriverHomeController@destroy')->name('delete-bannerimage');
Route::get('informationboard','DriverHomeController@createInformationBoard')->name('informationboard');
Route::post('informationboard/store','DriverHomeController@storeInformationBoard')->name('informationboard/store');
Route::get('informationboard/edit/{id}','DriverHomeController@editInformationBoard')->name('informationboard/edit/');
Route::post('informationboard/update/{id}','DriverHomeController@updateInformationBoard')->name('informationboard/update/');
Route::post('delete-info-board','DriverHomeController@deleteInfoBoard')->name('delete-info-board');

Route::resource('driver_of_month','DriverOfMonthController');
Route::post('delete-driver_of_month','DriverOfMonthController@destroy')->name('delete-driver_of_month');
Route::post('/deleteDriverOfMonth', ['uses'  => 'DriverOfMonthController@deleteDriverOfMonth','as'=> 'deleteDriverOfMonth'
]);

/*
|-----------------------------------------
| customer information routes
|-----------------------------------------
*/
Route::resource('customerinformationboard','CustomerInformationBoardController');
Route::post('informationboard/delete','CustomerInformationBoardController@destroy')->name('info-board-delete'); 
Route::post('informationboard/delete','CustomerInformationBoardController@destroy')->name('info-board-delete'); 
Route::post('deletequote', 'CustomerInformationBoardController@deletequote')->name('deletequote');

/*
|-----------------------------------------
| customer dynamic images routes
|-----------------------------------------
*/
Route::post('cust_dynamic_img', 'CustomerDynamicImagesController@createDynamicImg')->name('cust_dynamic_img');
Route::get('cust_dynamic_img/edit/{id}','CustomerDynamicImagesController@edit')->name('cust_dynamic_img/edit/');
Route::post('cust_dynamic_img_update','CustomerDynamicImagesController@update')->name('cust_dynamic_img_update');
