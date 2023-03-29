<?php


/*
|--------------------------------------------------------------------------
| Super Admin Routes Routes
|--------------------------------------------------------------------------
|
| Here is where you can add super admin routes.
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