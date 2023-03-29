<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|-----------------------------------------
| Authorization Routes
|-----------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

/*
|-----------------------------------------
| Super Administrator Routes
|-----------------------------------------
*/
Route::group(['middleware' => ['role:Super Admin']], function () {
    require 'super-admin.php';
});

/*
|-----------------------------------------
| Operator Management Routes
|-----------------------------------------
*/
require 'operators.php';

/*
|-----------------------------------------
| Customer Management Routes
|-----------------------------------------
*/
require 'customers.php';

/*
|-----------------------------------------
| VehicleFacilities Management Routes
|-----------------------------------------
*/
require 'vehiclefacilities.php';

/*
|-----------------------------------------
| Payments Management Routes
|-----------------------------------------
*/
require 'payments.php';

/*
|-----------------------------------------
| Information Management Routes
|-----------------------------------------
*/
require 'information-management.php';

/*
|-----------------------------------------
| Price Management Routes
|-----------------------------------------
*/
require 'price-management.php';

/*
|-----------------------------------------
| Notification Management Routes
|-----------------------------------------
*/
require 'notification.php';

/*
|-----------------------------------------
| Trip Management Routes
|-----------------------------------------
*/
require 'trip-management.php';

/*
|-----------------------------------------
| Subscription Management Routes
|-----------------------------------------
*/
require 'subscription.php';

/*
|-----------------------------------------
| API Routes to get dependent information
|-----------------------------------------
*/

Route::post('checkEmail','CommonController@checkEmail')->name('checkEmail');
Route::resource('getaddress', 'GetaddressController');
Route::post('getcitystate', 'GetaddressController@getCityState')->name('getcitystate');

/*
|-----------------------------------------
| Common Routes
|-----------------------------------------
*/
Route::get('home', 'HomeController@index')->name('home');
Route::get('register', 'HomeController@index')->name('home');
Route::post('/getNotification', ['uses'  => 'HomeController@getNotification','as'=> 'getNotification']);
Route::post('/getLatestNotification', ['uses'=> 'HomeController@getLatestNotification','as'=> 'getLatestNotification']);
Route::post('/markAsReadUserNotification', ['uses'=> 'HomeController@markAsReadUserNotification','as'=> 'markAsReadUserNotification']);
Route::post('/viewNotification', ['uses'=> 'HomeController@viewNotification','as'=> 'viewNotification']);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::post('change_password', 'Auth\ChangePasswordController@changePassword');

// Profile Routes...
Route::get('my_profile', 'Auth\ProfileController@showMyProfileForm')->name('auth.my_profile');
Route::post('my_profile', 'Auth\ProfileController@editProfile');
Route::get('get-admin-profile', 'Auth\ProfileController@getAdminProfile')->name('auth.get-admin-profile');

//feedback
Route::resource('feedback','FeedbackController');

Route::resource('deposite', 'DepositePaymentController');
Route::post('get-operator-details','DepositePaymentController@verifyOperator')->name('get-operator-details');
Route::get('deposite/edit/{id}','DepositePaymentController@edit')->name('deposite/edit/');
Route::get('deposite/show/{id}','DepositePaymentController@show')->name('deposite/show/');
Route::get('deposite/delete/{id}','DepositePaymentController@softdelete')->name('deposite/delete/');
Route::post('deposite/update','DepositePaymentController@update')->name('deposite/update');
Route::get('deposite/create','DepositePaymentController@create')->name('deposite/create');
Route::post('deposite/store','DepositePaymentController@store')->name('deposite/store');
Route::post('getifsccodedb','DepositePaymentController@getifsccode')->name('getifsccodedb');
    
Route::group(['middleware' => ['auth']], function () 
{
    //authorization routes
});

Route::resource('setting','SettingController');
Route::post('save-admin-bank', 'SettingController@saveBankInfo')->name('save-admin-bank');
Route::post('update-default-admin-bank', 'SettingController@setDefaultBank')->name('update-default-admin-bank');
Route::post('check-bank-is-default-delete', 'SettingController@checkisDeleteBank')->name('check-bank-is-default-delete');
Route::post('delete-admin-bank', 'SettingController@deleteBank')->name('delete-admin-bank');
Route::get('edit-bankinfo/{id}', 'SettingController@editBankInfo')->name('edit-bankinfo');
Route::post('update-bankinfo', 'SettingController@UpdateBankInfo')->name('update-bankinfo');
Route::post('switch-sms-gateway', 'SettingController@switchSmsGateway')->name('switch-sms-gateway');
Route::post('overtime-charges', 'SettingController@overtimeCharges')->name('overtime-charges');
Route::get('/ledger', function () {
    return view('admin.payments.operatorPayments.ledger');
});
/*Route::get('/invoice', function () {
    return view('admin.payments.operatorPayments.invoices');
});*/
//subscription
Route::get('/loyalty', function () {
    return view('admin.subscriptionplan.loyalty');
});
//trip management
Route::get('/transaction_detail', function () {
    return view('admin.orders.transaction_detail');
});
//reports
Route::get('/generate_reports', function () {
    return view('admin.reports.generate_reports');
});

Route::post('generate-report', 'GenerateReportController@generateReport')->name('generate-report');
Route::get('/invoice', 'InvoiceController@index')->name('invoice');
Route::post('generate-invoice', 'InvoiceController@generateInvoice')->name('generate-invoice');
//Route::get('pdfview-partner',array('as'=>'pdfview-partner','uses'=>'InvoiceController@pdfviewPartner'));
Route::get('pdfview-partner', 'InvoiceController@pdfviewPartner')->name('pdfview-partner');

