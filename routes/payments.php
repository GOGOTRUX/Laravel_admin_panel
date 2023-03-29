<?php


/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
|
| Here is where you can add payment and razorpay routes.
|
*/
//operator payment routes
Route::resource('payments','OperatorPaymentsController');
Route::post('get-payment-details/operator','OperatorPaymentsController@getOperatorPaymentDetails')->name('get-payment-details/operator');
Route::post('get-operator-list','OperatorPaymentsController@getOperatorList')->name('get-operator-list');
Route::post('getPlanDetails','OperatorPaymentsController@getPlanDetails')->name('getPlanDetails');
Route::post('delete-payment','OperatorPaymentsController@deletePayment')->name('delete-payment');
Route::post('mark-as-received-payment','OperatorPaymentsController@markAsReceived')->name('mark-as-received-payment');
Route::post('send-for-approval-payment','OperatorPaymentsController@sendForApproval')->name('send-for-approval-payment');
Route::get('/payment-view/{id}/','OperatorPaymentsController@viewPayment')->name('payment-view');
Route::get('/getTransactionID','CommonController@generateTransactionID')->name('getTransactionID');
Route::get('/payment-credit-debit-note','OpCreditDebitDetails@index')->name('payment-credit-debit-note');
Route::post('is-plan-valid','OperatorPaymentsController@isValidPlan')->name('is-plan-valid');
Route::get('approve/payment/{id}','OperatorPaymentsController@ApprovePaymentBySuperAdmin')->name('approve/payment');
Route::post('update-payment-status','OperatorPaymentsController@updatePaymentStatus')->name('update-payment-status');

//customer payments routes
Route::resource('customer-payments','CustomerPaymentsController');
Route::post('get-payment-details/customer','CustomerPaymentsController@getCustomerPaymentDetails')->name('get-payment-details/customer');
Route::post('send-for-approval-customer-payment','CustomerPaymentsController@sendForApproval')->name('send-for-approval-customer-payment');
Route::post('markAsReceived-customer-payment','CustomerPaymentsController@markAsReceived')->name('markAsReceived-customer-payment');
//get-customer-trips
Route::post('get-customer-trips','CustomerPaymentsController@getCustomerTrips')->name('get-customer-trips');

//payment-invoice pdf
Route::get('pdfview',array('as'=>'pdfview','uses'=>'PaymentInvoiceController@pdfview'));

//credit debit notes
Route::resource('paymentsCreditDebit','OpCreditDebitDetails');
Route::post('/paymentsCreditDebit/approve/{id}','OpCreditDebitDetails@approvePaymentNote')->name('/paymentsCreditDebit/approve');

/*
|-----------------------------------------
| Razorpay Routes
|-----------------------------------------
*/

//razor pay route
Route::get('paywithrazorpay', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
Route::post('payment', 'RazorpayController@payment')->name('payment');
Route::post('getFullResponse', 'RazorpayController@getFullResponse')->name('getFullResponse');
// Get Route For Show Payment Form
Route::get('paywithrazorpay', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
// Post Route For Make Payment Request
Route::post('payment','RazorpayController@payment')->name('payment');

