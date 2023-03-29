<?php


/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
|
| Here is where you can add notification (bell icon) and notification box routes.
|
*/

Route::resource('roles', 'RolesController');
Route::post('roles_mass_destroy', ['uses' => 'RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
Route::post('delete-role','RolesController@destroy')->name('delete-role');
Route::post('/checkHasPermission', [
    'uses'  => 'RolesController@checkHasPermission',
    'as'    => 'checkHasPermission'
]);

Route::resource('users', 'UsersController');
Route::post('users_mass_destroy', ['uses' => 'UsersController@massDestroy', 'as' => 'users.mass_destroy']);
Route::post('delete-user','UsersController@destroy')->name('delete-user');


/*
|-----------------------------------------
| Notification Detail Routes (mail notification)
|-----------------------------------------
*/
Route::resource('notifications', 'NotificationToDoController');
Route::get('/notification/view/{notification_id}', 'NotificationToDoController@viewNotification')->name('/notification/view');
Route::get('home/view/notificationbox/{notification_type?}', 'NotificationToDoController@viewNotificationBox')->name('home.notificationbox');
Route::post('/delete_admin_notification', ['uses' => 'NotificationToDoController@delete_admin_notification','as'=> 'delete_admin_notification']); 
Route::post('/deleteMultipleMail','NotificationToDoController@deleteMultipleMail');
Route::post('/alldetailmail', ['uses' => 'NotificationToDoController@alldetailmail','as'=> 'alldetailmail']);
Route::post('/viewNotificationMail', ['uses' => 'NotificationToDoController@viewNotificationMail','as'=> 'viewNotificationMail']);
Route::post('/viewNotificationDetail', ['uses' => 'NotificationToDoController@viewNotificationDetail','as'=> 'viewNotificationDetail']);
Route::post('/updateSubcriptionDetail', ['uses' => 'NotificationToDoController@updateSubcriptionDetail','as'=> 'updateSubcriptionDetail']);