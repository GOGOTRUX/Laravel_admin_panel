<?php


/*
|--------------------------------------------------------------------------
| Subscription Management Routes
|--------------------------------------------------------------------------
|
| Here is where you can add subscription routes.
|
*/

Route::resource('subscriptions','SubscriptionplanController');
Route::post('CheckSubscriptionPlan','SubscriptionplanController@checkSubscriptionPlan')->name('CheckSubscriptionPlan');
Route::get('autoCompleteSubTypeName','SubscriptionplanController@getAutoCompleteSubTypeName')->name('autoCompleteSubTypeName');
Route::get('checkSubType','SubscriptionplanController@checkSubScriptionType')->name('checkSubType');
Route::post('delete/subpan','SubscriptionplanController@destroy')->name('delete/subpan');
Route::post('verify/subplan','SubscriptionplanController@verifySubPlanBySuperAdmin')->name('verify/subplan');
Route::post('approve/subplan','SubscriptionplanController@ApproveSubPlanBySuperAdmin')->name('approve/subplan');
Route::get('view/subplan/notification','SubscriptionplanController@redirectToSubplanNotification')->name('view-subplan-notification');

///subscription types
Route::resource('subscriptiontypes','SubscriptiontypesController');
Route::post('subscriptiontypeselectdelete','SubscriptiontypesController@deleteselected')->name('subscriptiontypeselectdelete');
Route::post('CheckSubscriptionScheme','SubscriptiontypesController@checkSubscriptionScheme')->name('CheckSubscriptionScheme');
Route::post('getsubscriptionlogo', 'SubscriptiontypesController@getSubscriptionLogo')->name('getsubscriptionlogo');
