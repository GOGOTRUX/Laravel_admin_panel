<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperatorPayments;
use App\Models\subscriptionplan;
use App\Models\Operator;
use PDF;

class PaymentInvoiceController extends Controller
{
    public function pdfview(Request $request)
    {
        $items = OperatorPayments::get();
        view()->share('items',$items);


        if($request->has('download')){
        	// dd($request->all());
            $payment_id = decrypt($request->download);

            $payment_details = OperatorPayments::where('op_order_id', $payment_id)->first();
            if(!empty($payment_details)){
                if(!empty($payment_details['op_user_id'])){
                    $op_details = Operator::select('op_first_name', 'op_uid')->where('op_user_id', $payment_details['op_user_id'])->first();
                    $payment_details['op_name'] = $op_details['op_first_name'];
                }
                else{
                    $payment_details['op_name'] = null;
                }
                if(!empty($payment_details['op_order_payment_p_details'])){
                    $payment_p_details = json_decode($payment_details['op_order_payment_p_details'], true);
                    if(!empty($payment_p_details['sub_scheme_name'])){
                        $plan_name = subscriptionplan::where('subscription_id', $payment_p_details['sub_scheme_name'])->value('subscription_type_name');
                        $payment_p_details['sub_scheme_name'] = $plan_name;
                    }
                    $payment_details['payment_p_details'] = $payment_p_details;
                }
                else{
                    $payment_details['payment_p_details'] = null;
                }
            }
            else{
                $payment_details['op_name'] = null;
                $payment_details['payment_p_details'] = null;
            }
            $pdf = PDF::loadView('admin.payments.operatorPayments.payment_invoice', $payment_details);
            return $pdf->download($payment_details['op_order_mobile_no'].'_'.$payment_details['op_order_payment_purpose'].'.pdf');
        }

        // return view('admin.operatorPayments.payment_invoice');
    }
}
