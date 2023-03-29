<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerBookTrip;
use App\Models\UserPayments;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\NumberFormatter;
use App\Models\BankMaster;
use App\Models\CitiesMaster;
use PDF;

class InvoiceController extends Controller
{
    public function index(){
        return view('admin.payments.operatorPayments.invoices');
    }

    public function generateInvoice(Request $request){
    	$postData = $request->all();
    	if(!empty($postData)){
    		if(isset($postData['invoice_from_date']) && isset($postData['invoice_to_date'])){
    			
    				$getTripData = CustomerBookTrip::
			        join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
			        ->join('ggt_user','ggt_user.user_id','=','ggt_user_book_trip.user_id')
			        ->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
			        ->select('ggt_user_payments.updated_at','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.cust_invoice_no','ggt_user_book_trip.op_invoice_no','ggt_user_book_trip.base_amount','ggt_user_book_trip.loader_price','ggt_user_book_trip.ggt_factor','ggt_user_book_trip.actual_amount','ggt_user_book_trip.ride_status','ggt_user.user_uid','ggt_user_payments.user_order_transaction_id','ggt_user_payments.user_order_paylink_id','ggt_user_payments.user_order_status','ggt_user_book_trip.user_adjustment','ggt_user_book_trip.op_adjustment','ggt_user_book_trip.ggt_adjustment')
				->whereNull('ggt_user_book_trip.deleted_at')
				->where('ggt_user_book_trip.ride_status','=','success')
			        ->whereDate('ggt_user_payments.updated_at','>=',$postData['invoice_from_date'])
			        ->whereDate('ggt_user_payments.updated_at','<=',$postData['invoice_to_date'])
			        ->get()
			        ->toArray();
			        if($getTripData){
	    				//create table start
	    				$tid = "outward_".$postData['invoice_type'];
				        $tb = '<table id="'.$tid.'" class="table table-list" data-page-length="10"><thead><tr><th>Date</th><th>Time</th><th>Transaction ID</th><th>Trip ID</th><th>Cust ID</th><th>Invoice No</th><th>Base</th><th>Loader</th><th>Incidental</th><th>Extension</th><th>Others</th><th>GST</th><th>GGT</th><th>Total</th><th>Status</th><th>Flag <i class="fa fa-flag"></i></th></tr></thead>';
				        foreach ($getTripData as $key => $value) {
					        if($value['updated_at'] != null || $value['updated_at'] != ''){
					            $datetime = (explode(" ",$value['updated_at']));
					            $getTripData[$key]['time'] = date("g:i a", strtotime($datetime[1]));
					            $getTripData[$key]['date'] = $datetime[0];
					        }
					        else{
					            $getTripData[$key]['time'] = '-';
					            $getTripData[$key]['date'] = '-';
					        }
					        if(isset($value['user_order_transaction_id'])){
					        	$getTripData[$key]['user_order_transaction_id'] = $value['user_order_transaction_id'];
					        }else{
					        	$getTripData[$key]['user_order_transaction_id'] = $value['user_order_paylink_id'];	
					        }
					        if($value['user_order_status'] == 'approved'){
					        	$getTripData[$key]['user_order_status'] = 'Paid';
					        }
						$value['actual_amount'] = $value['actual_amount'] + ($value['user_adjustment']);
						$value['base_amount'] = $value['base_amount'] + ($value['op_adjustment']);
						$value['ggt_factor'] = $value['ggt_factor'] + ($value['ggt_adjustment']);
					    }
					    $tbody = '<tbody>';
					    foreach ($getTripData as $key => $value) {
						$value['actual_amount'] = $value['actual_amount'] + ($value['user_adjustment']);
                                                $value['base_amount'] = $value['base_amount'] + ($value['op_adjustment']);
                                                $value['ggt_factor'] = $value['ggt_factor'] + ($value['ggt_adjustment']);
						if($postData['invoice_type'] == 'outward'){
					    		$invoice_no = $value['cust_invoice_no'];
					    	}else{
					    		$invoice_no = $value['op_invoice_no'];
					    	}
					        $tbody .= '<tr><td>'.$value['date'].'</td><td>'.$value['time'].'</td><td>'.$value['user_order_transaction_id'].'</td><td>'.$value['trip_transaction_id'].'</td><td>'.$value['user_uid'].'</td><td><a onclick="printInvoice(\''.$value["trip_transaction_id"].'\')">'.$invoice_no.'</a></td><td>'.$value['base_amount'].'</td><td>'.$value['loader_price'].'</td><td>-</td><td>-</td><td>-</td><td>-</td><td>'.$value['ggt_factor'].'</td><td>'.$value['actual_amount'].'</td><td>'.$value['user_order_status'].'</td><td>-</td></tr>';
				        }
				        $tbody .= '</tbody></table>';
				        $tb .= $tbody;	
				        $response = ['status' => 'success', 'invoice' => $tb, 'tid' => $tid, 'message' => 'invoices generated','statusCode' => Response::HTTP_OK];
				        return response()->json(['response'=> $response]);
				        //create table end
			
    			}else{
    				$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        		return response()->json(['response'=> $returnResponse]);
    			}
		    }else{
		    	$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        	return response()->json(['response'=> $returnResponse]);
		    }
    	}else{
    		$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
    	}
    }

    public function pdfviewPartner(Request $request){
	$postData = $request->all();	
        if(!empty($postData)){
        	// dd($request->all());
            $trip_transaction_id = $postData['tripid'];
	    $invoice_type = $postData['invoice_type'];
            $trip_details = CustomerBookTrip::
		        join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')
			->join('ggt_user','ggt_user.user_id','=','ggt_user_book_trip.user_id')
		        ->join('ggt_user_payments', 'ggt_user_book_trip.pay_order_id', '=', 'ggt_user_payments.user_order_id')
		        ->select('ggt_user.user_first_name','ggt_user.user_last_name','ggt_user.user_mobile_no','ggt_user.email','user_address_line','user_address_line_1','user_address_line_2','user_address_line_3','address_pin_code','address_city','address_state','ggt_operator_users.op_mobile_no','op_first_name','op_last_name','op_address_line_1','op_address_city','op_address_pin_code','op_address_state','op_bank_name','op_bank_ifsc','op_bank_account_number','ggt_user_payments.updated_at','ggt_user_book_trip.trip_transaction_id','ggt_user_book_trip.cust_invoice_no','ggt_user_book_trip.op_invoice_no','ggt_user_book_trip.base_amount','ggt_user_book_trip.loader_price','ggt_user_book_trip.actual_amount','ggt_user_book_trip.ride_status','ggt_user_payments.user_order_transaction_id','ggt_user_payments.user_order_paylink_id','ggt_user_payments.user_order_status','ggt_user_book_trip.user_adjustment','ggt_user_book_trip.op_adjustment','ggt_user_book_trip.ggt_adjustment',
					'ggt_user_book_trip.start_address_line_1',
					'ggt_user_book_trip.dest_address_line_1',
					'ggt_user_book_trip.weight',
					'ggt_user_book_trip.total_distance',
					'ggt_user_book_trip.material_type',
					'ggt_user_book_trip.cust_waiting_charges',
					'ggt_user_book_trip.partner_waiting_charges',
					'ggt_user_book_trip.incidental_charges',
					'ggt_user_book_trip.accidental_charges',
					'ggt_user_book_trip.other_charges',
					'ggt_user_book_trip.updated_at',
					'ggt_user_book_trip.created_at',
					'ggt_user_book_trip.book_date',
					'ggt_user_book_trip.payment_type')
		        ->where('ggt_user_book_trip.trip_transaction_id',$trip_transaction_id)
		        ->first();
            if(!empty($trip_details)){
		//get bank name
            	$bank_name = BankMaster::select('op_bank_name')->where('id',$trip_details->op_bank_name)->first();
            	//get state and city
		if(isset($trip_details->op_address_city)){
            	$get_state_city=CitiesMaster::join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$trip_details->op_address_city)->first();
            	$trip_details->op_address_city = $get_state_city->city;
            	$trip_details->op_address_state = $get_state_city->state;	
		}
		if(isset($trip_details->address_city)){
		$get_state_city_cust=CitiesMaster::join('ggt_master_states','ggt_master_states.id','=','ggt_master_cities.state_id')->select('ggt_master_cities.state_id','ggt_master_states.state','ggt_master_cities.id','ggt_master_cities.city')->where('ggt_master_cities.id','=',$trip_details->address_city)->first();
                $trip_details->address_city = $get_state_city_cust->city;
                $trip_details->address_state = $get_state_city_cust->state;
		}
            	$trip_details->op_bank_name = $bank_name->op_bank_name;
            	$trip_details->op_first_name = $trip_details->op_first_name.' '.$trip_details->op_last_name;
		$trip_details->user_first_name = $trip_details->user_first_name.' '.$trip_details->user_last_name; 
                $trip_details->address = $trip_details->op_address_line_1.' '.$trip_details->op_address_line_2.' '.$trip_details->op_address_line_3.' '.$trip_details->op_address_line_4.' '.$trip_details->op_address_pin_code.' '.$trip_details->op_address_city.' '.$trip_details->op_address_state;
		$trip_details->cust_address = $trip_details->user_address_line.' '.$trip_details->user_address_line_1.' '.$trip_details->user_address_line_2.' '.$trip_details->user_address_line_3.' '.$trip_details->address_pin_code.' '.$trip_details->address_city.' '.$trip_details->address_state;
              
                $partner_amount = $trip_details->base_amount + ($trip_details->op_adjustment);
                $trip_details->base_amount = $partner_amount;
				$amount_in_words_partner = $this->AmountInWords($partner_amount);
				$trip_details->amount_in_words = $amount_in_words_partner;
				//$othercharges = $trip_details->cust_waiting_charges + $trip_details->partner_waiting_charges + $trip_details->incidental_charges + $trip_details->accidental_charges + $trip_details->other_charges;
				$othercharges = $trip_details->incidental_charges + $trip_details->other_charges;		                
				$trip_details->othercharges = $othercharges;
                		$customer_amount = $trip_details->actual_amount + ($trip_details->user_adjustment) + ($othercharges);

		//$customer_amount = $trip_details->actual_amount + ($trip_details->user_adjustment);
				$trip_details->without_charges = $trip_details->actual_amount + ($trip_details->user_adjustment);

				$trip_details->actual_amount = $customer_amount;
                                $amount_in_words_cust = $this->AmountInWords($customer_amount);
                                $trip_details->amount_in_words_cust = $amount_in_words_cust;
				if(isset($trip_details->user_order_transaction_id)){
					$trip_details->user_order_transaction_id = $trip_details->user_order_transaction_id;
				}else{
					$trip_details->user_order_transaction_id = $trip_details->user_order_paylink_id;
				}
				$trip_date_time = $trip_details->book_date;
				$trip_date_time = explode(' ', $trip_date_time); 
				$trip_details->trip_date = $trip_date_time[0];
				$trip_details->trip_time = $trip_date_time[1];
				$book_date_time = $trip_details->created_at;
				$book_date_time = explode(' ', $book_date_time);
				$trip_details->book_date = $book_date_time[0];
				$trip_details->book_time = $book_date_time[1];
            }
            else{
                $trip_details = null;
            }
	    if($invoice_type == 'inward'){
            	$pdf = PDF::loadView('admin.payments.operatorPayments.partner_invoice', $trip_details);
	    }else{
	    	//$pdf = PDF::loadView('admin.payments.operatorPayments.customer_invoice', $trip_details);
		$pdf = PDF::loadView('admin.payments.operatorPayments.customer_invoice_new', $trip_details)->setPaper('a4', 'landscape');
            }
            //return $pdf->download($trip_details->trip_transaction_id.'.pdf');
		return $pdf->stream();
	    //return view('admin.payments.operatorPayments.partner_invoice',compact('trip_details'));
	    //return 1;
        }else{
        	$returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
	        return response()->json(['response'=> $returnResponse]);
        }
    }

	public function AmountInWords(float $amount)
{
   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $x < $count_length ) {
      $get_divider = ($x == 2) ? 10 : 100;
      $amount = floor($num % $get_divider);
      $num = floor($num / $get_divider);
      $x += $get_divider == 10 ? 1 : 2;
      if ($amount) {
       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
        }
   else $string[] = null;
   }
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}
}
