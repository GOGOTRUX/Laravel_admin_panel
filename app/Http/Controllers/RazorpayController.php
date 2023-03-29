<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Razorpay\Api\Api;
use Session;
use Redirect;

class RazorpayController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new Api(config('custom_config_file.razor_key'), config('custom_config_file.razor_secret'));
    }

    public function payWithRazorpay()
    {        
        return view('payWithRazorpay');
    }

    public function payment()
    {
        //Input items of form
        $input = Input::all();
        //get API Configuration 
        //Fetch payment information by razorpay_payment_id
        $payment = $this->api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) 
        {
            try {
                $response = $this->api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 

            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }
// dd($response );
            // Do something here for store payment details in database...
        }
        
        \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        return redirect()->back();
    }

    public function getFullResponse(Request $requst){
        $payment = $this->api->payment->fetch($requst->payment_id);
        $notes = $payment_info = [];
        if(!empty($payment)){
            foreach ($payment as $key => $value) {
                $payment_info[$key] = $payment[$key];
                if($key == 'notes'){
                    $payment_info[$key] = [];
                    if(!empty($value)){
                        foreach ($value as $key1 => $value1) {
                            $payment_info[$key][$key1] = $value[$key1];
                        }
                    }
                }
            }
        }
        else{
            $payment_info = [];
        }
        return json_encode($payment_info);
    }
}