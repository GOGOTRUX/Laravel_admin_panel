<?php


/*
|--------------------------------------------------------------------------
| Trip Management Routes
|--------------------------------------------------------------------------
|
| Here is where you can add mytrips and realtime assistance routes.
|
*/

/*
|-----------------------------------------
| Real Time Assistance Routes
|-----------------------------------------
*/

Route::resource('realtime-assistance', 'RealTimeAssistanceController');
Route::post('showTripDetail','RealTimeAssistanceController@showTripDetail')->name('RealTimeDetail');
Route::get('realtimedata','RealTimeAssistanceController@realtimedata');
Route::get('realtimeAjaxData','RealTimeAssistanceController@realtimeAjaxData');


/*
|-----------------------------------------
| Order i.e Trips Routes
|-----------------------------------------
*/

Route::resource('Orders', 'OrdersController');
Route::get('/transaction_detail/{id}','OrdersController@gettripdetail')->name('tripinfo');
Route::post('updatedisputed', ['uses'  => 'OrdersController@updatedisputed','as'    => 'updatedisputed' ]);
Route::get('ordersAjaxData','OrdersController@ordersAjaxData');
Route::post('add-trip-edit-from-realtime','RealTimeAssistanceController@addTripEditFromRealtime')->name('add-trip-edit-from-realtime');
/*
|-----------------------------------------
| Add Trips Routes
|-----------------------------------------
*/
Route::get('add-trip','AddTripController@addTrip')->name('add-trip');
Route::get('add-trip-delivery','AddTripController@tripDelivery')->name('add-trip-delivery');
Route::post('get-vehicle-list-withoutbid','AddTripController@tripdata')->name('get-vehicle-list-withoutbid');
Route::post('updatetoken', ['uses'  => 'AddTripController@updateToken','as'    => 'updatetoken' ]);
Route::post('send-booking', ['uses'  => 'AddTripController@CustomerRegistrationDetail','as'    => 'send-booking' ]);
Route::post('confirm-book','AddTripController@getTripConfirmNotificationDetails')->name('confirm-book');
Route::post('make-trip-pay','AddTripController@makeTripPayment')->name('make-trip-pay');
//Route::get('get-trip-pay-link-response/{id}','AddTripController@getPaymentLinkResponse')->name('get-trip-pay-link-response');
Route::get('get-trip-pay-link-response/{id}/{notiId}','AddTripController@getPaymentLinkResponse')->name('get-trip-pay-link-response');
Route::post('close-trip-status','AddTripController@closeTripStatus')->name('close-trip-status');
Route::get('add-vehicle-images/{id}','AddTripController@getVehicleAllImages')->name('add-vehicle-images');
Route::post('book-trip','AddTripController@bookTrip')->name('book-trip');
Route::post('send-trip-invoice','AddTripController@sendTripInvoice')->name('send-trip-invoice');
Route::post('verify-cash-otp','AddTripController@verifyOtp')->name('verify-cash-otp');
Route::post('close-trip-pin','AddTripController@closeTripPin')->name('close-trip-pin');


Route::get('send-mail','AddTripController@sendMail')->name('send-mail');

Route::post('save-trip','AddTripController@saveTrip')->name('save-trip');
Route::post('add-trip-edit','AddTripController@editAddTrip')->name('add-trip-edit');
Route::post('post-trip','AddTripController@postTrip')->name('post-trip');
Route::get('fb-bg-data/{id}','AddTripController@bgFbData')->name('fb-bg-data');
Route::post('other-charges','AddTripController@otherCharges')->name('other-charges');
