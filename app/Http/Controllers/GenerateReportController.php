<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;
use App\Models\Vehicles;
use App\Models\StatesMaster;
use App\Models\CitiesMaster;
use App\Models\PincodesMaster;
use App\Models\BankMaster;
use DB;
use App\Models\UserPayments;
use App\Models\TempCustomerBookTrip;
use App\Models\CustomerBookTrip;
use App\Models\Operator;

class GenerateReportController extends Controller
{
    public function generateReport(Request $request){
    	$postData = $request->all();//dd($postData);
    	if(!empty($postData)){
    		if(isset($postData['select_report'])){
    			if($postData['select_report'] == 'customer'){
                    $type = 'customer';
                    $id = 'report_1';
                    $selectInput = $postData['report_input'];
                    $data = 'select '.$selectInput.' from ggt_user';
                    if(isset($postData['cust_deleted'])){
                    	$data = $data.' where deleted_at is not null';
                    }
                    if(isset($postData['cust_blocked'])){
                    	if(isset($postData['cust_deleted'])){
                    		$data = $data.' and is_blocked=1';
                    	}else{
                    		$data = $data.' where is_blocked=1';
                    	}	
                    }
                    if(isset($postData['cust_verified'])){
                    	if(isset($postData['cust_deleted']) || isset($postData['cust_blocked'])){
                    	$data = $data.' and user_verified =1'; 
                    	}else{
                    		$data = $data.' where user_verified =1';
                    	}
                    }
                    if(isset($postData['cust_deleted']) || isset($postData['cust_blocked']) || isset($postData['cust_verified'])){
                    	//$data = $data.' and created_at between "'.$postData['report_from_date'].'" and "'.$postData['report_to_date'].'"';
			$data = $data.' and date(`ggt_user`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_user`.`created_at`) <= "'.$postData['report_to_date'].'"';
                    }else{
                    	//$data = $data.' and created_at between "'.$postData['report_from_date'].'" and "'.$postData['report_to_date'].'" and deleted_at is null';
			$data = $data.' where date(`ggt_user`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_user`.`created_at`) <= "'.$postData['report_to_date'].'" and ggt_user.deleted_at is null';
                    }
            	}else if($postData['select_report'] == 'partner'){
                    $type = 'partner';
                    $id = 'report_2';
                    $selectInput = $postData['report_input'];
                    $from = $postData['report_from_date'];
                    $to = $postData['report_to_date'];
		    $deletevehicle = false;
                    $data = 'select '.$selectInput.' from `ggt_operator_users`';
                    if(isset($postData['veh_make_model_type']) || isset($postData['veh_model_name']) || isset($postData['veh_registration_no']) || isset($postData['veh_city']) || isset($postData['veh_last_location_updated_at'])){
    					$vehicleJoin = ' inner join `ggt_op_vehicles` on `ggt_operator_users`.`op_user_id` = `ggt_op_vehicles`.`veh_op_id`';
    					$data = $data.$vehicleJoin;
					$data = $data." where `ggt_op_vehicles`.`is_deleted` = '0'";
    					$deletevehicle = true;
    				}
                    
                    if(isset($postData['op_type_id'])){
					if($deletevehicle == true){
    					if($postData['op_type_id'] == 'both'){
    						$data = $data." and (op_type_id = '1' or op_type_id = '2')"; 
    					}else{
    						$data = $data.' and op_type_id="'.$postData['op_type_id'].'"';
    					}
					}else{
						if($postData['op_type_id'] == 'both'){
                                                $data = $data." where (op_type_id = '1' or op_type_id = '2')";
                                        }else{
                                                $data = $data.' where op_type_id="'.$postData['op_type_id'].'"';
                                        }

					}	
    				}
    				if(isset($postData['deleted_at'])){
    					if(isset($postData['op_type_id']) || $deletevehicle == true){
    						$data = $data." and deleted_at is not null";
    					}else{
    						$data = $data." where deleted_at is not null";
    					}
    				}
    				if(isset($postData['op_is_blocked'])){
    					if(isset($postData['op_type_id']) || isset($postData['deleted_at']) || $deletevehicle == true){
    						$data = $data." and op_is_blocked=1";
    					}else{
    						$data = $data." where op_is_blocked=1";
    					}
    				}
                    if(isset($postData['op_is_verified'])){
                    	if(isset($postData['op_type_id']) || isset($postData['deleted_at']) || isset($postData['op_is_blocked']) || $deletevehicle == true){
                            $data = $data.' and op_is_verified='.$postData['op_is_verified'];
                        }else{
                        	$data = $data.' where op_is_verified='.$postData['op_is_verified'];
                        }
                    }//isset($postData['op_type_id'])
                    if(isset($postData['deleted_at']) || isset($postData['op_is_blocked']) || isset($postData['op_is_verified']) || $deletevehicle == true){		
			if(isset($postData['created_at'])){
			$data = $data.' and date(`ggt_operator_users`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_operator_users`.`created_at`) <= "'.$postData['report_to_date'].'"';
                    	
			}else{
			$data = $data.' and date(`ggt_operator_users`.`updated_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_operator_users`.`updated_at`) <= "'.$postData['report_to_date'].'"';
			}
                    }else if(isset($postData['veh_last_location_updated_at'])){
			$data = $data.' where date(`ggt_op_vehicles`.`updated_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_op_vehicles`.`updated_at`) <= "'.$postData['report_to_date'].'"';
		    }else if(isset($postData['op_type_id'])){
			$data = $data.' and date(`ggt_operator_users`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_operator_users`.`created_at`) <= "'.$postData['report_to_date'].'"';
		    }
		    else{
                    	$data = $data.' where date(`ggt_operator_users`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_operator_users`.`created_at`) <= "'.$postData['report_to_date'].'"';
                    }   
    			}else if($postData['select_report'] == 'subscription'){
    			}else if($postData['select_report'] == 'trip_revenue'){	
    				$selectInput = $postData['report_input'];
    				$id = 'report_4';
    				$type = 'trip';
    				if(isset($postData['cust-name'])){
    					$selectInput = $selectInput.',ggt_user.user_first_name,ggt_user.user_last_name';
    					$custJoin = 'inner join `ggt_user` on `ggt_user_book_trip`.`user_id` = `ggt_user`.`user_id`';
    				}
    				if(isset($postData['cust-mobile'])){
    					$selectInput = $selectInput.',ggt_user.user_mobile_no';
    					$custJoin = 'inner join `ggt_user` on `ggt_user_book_trip`.`user_id` = `ggt_user`.`user_id`';
    				}
    				if(isset($postData['op_first_name'])){
    					$selectInput = $selectInput.',ggt_operator_users.op_first_name,ggt_operator_users.op_last_name';
    					$partnerJoin = 'inner join `ggt_operator_users` on `ggt_user_book_trip`.`op_id` = `ggt_operator_users`.`op_user_id`';
    				}
    				if(isset($postData['op_mobile_no'])){
    					$selectInput = $selectInput.',ggt_operator_users.op_mobile_no';
    					$partnerJoin = 'inner join `ggt_operator_users` on `ggt_user_book_trip`.`op_id` = `ggt_operator_users`.`op_user_id`';
    				}
    				$data = 'select '.$selectInput.' from `ggt_user_book_trip`';
    				if(isset($custJoin)){
    					$data = $data.$custJoin; 
    				}
    				if(isset($partnerJoin)){
    					$data = $data.$partnerJoin;
    				}
    				if(isset($postData['trip-pay'])){
    					if($postData['trip-pay'] == 'both'){
    						$data = $data."where (payment_type = 'digital' or payment_type = 'cash')"; 
    					}else{
    						$data = $data.'where payment_type="'.$postData['trip-pay'].'"';
    					}	
    				}
				if(isset($postData['trip-status'])){
					if(isset($postData['trip-pay'])){
                        			$data = $data.' and ride_status = "'.$postData['trip-status'].'"';
					}else{
						$data = $data.' where ride_status = "'.$postData['trip-status'].'"';
					}		
                   		 }/*else{
					if(isset($postData['trip-pay'])){
                                                $data = $data.' and ride_status = "success"';
                                        }else{
                                                $data = $data.' where ride_status = "success"';
                                        }

				 }*/
    				$data = $data.' and `ggt_user_book_trip`.`pay_order_id` is not null and `ggt_user_book_trip`.`deleted_at` is null and date(`ggt_user_book_trip`.`created_at`) >= "'.$postData['report_from_date'].'" and date(`ggt_user_book_trip`.`created_at`) <= "'.$postData['report_to_date'].'"';
    			}else if($postData['select_report'] == 'receive_payment'){

    			}else if($postData['select_report'] == 'customer_search'){
				$type = 'customer_search';
                    $id = 'report_6';
                    $selectInput = $postData['report_input'];
                    $from = $postData['report_from_date'];
                    $to = $postData['report_to_date'];

    				$custSearchData = TempCustomerBookTrip::select('*')->limit(12)->orderBy('id','desc')->get()->toArray();
                    if(!empty($custSearchData)){
                        foreach ($custSearchData as $key => $value) {
                            $getOpIds = CustomerBookTrip::select('op_id')->where('temp_book_trip_id',$value['id'])->get()->toArray();
                            if(!empty($getOpIds)){
                                $getOpName = Operator::select('op_user_id','op_first_name','op_last_name','op_uid')->whereIn('op_user_id',$getOpIds)->get()->toArray();
                            }else{
                                $getOpName = null;
                            }
                            $custSearchData[$key]['opdetails'] = $getOpName;
                            if(!empty($value['start_address_line_1'])){
                                // dd($value['start_address_line_1']);
                                // $from = (explode(' ',trim($value['start_address_line_1'])))[0];   
                                $custSearchData[$key]['from']= $value['start_address_line_1'];   
                            }
                            else{
                                $custSearchData[$key]['from']= "";
                            }
                            if(!empty($value['intermediate_address'])){
                                $destinationaddress= json_decode($value['intermediate_address'],true);
                                $temp = [];
                                if(!empty($destinationaddress)){
                                    foreach ($destinationaddress as $key1 => $value1) {
                                        // $firstWord = explode(' ',trim($value1['intermediate_location']))[0];
                                        // array_push($temp, $value1['intermediate_location']);
                                        $test = array_map(function ( $item ) {
                                        $arr = explode(',', $item['intermediate_location']);
                                        $offset = (count($arr) > 3) ? - 3 : -2;

                                        array_splice($arr, $offset);

                                        return implode(',', $arr);
                                        }, $destinationaddress);

                                    }
                                    // dd($value1['intermediate_location']);
                                    $to = implode(',',$test);
                                    $custSearchData[$key]['to']= $to;
                                }

                            }
                            else{
                                $custSearchData[$key]['to']= "";
                            }
                        }
                        $custData_array = array();
			//dd($custSearchData);
                        if($custSearchData){
			    $tb = '<table id="'.$id.'" class="table table-list" data-page-length="25">';                                    
                                $str2 = '<tbody>';
                                foreach($custSearchData as $key => $value){
                                        $str2 .= "<tr>";
                                        foreach ($value as $key1 => $value1)
                                        {
                                                $custData_array[$key1][] = $value1;
                                                $str2 .= '<td>'.$value1.'</td>';
                                        }
                                        $str2 .= "</tr>";
                                    }
                                    $str2 .= '</tbody>';

                                $str1 = '<thead><tr>';
                                    foreach($custData_array as $key => $value)
                                    {
                                        $str1 .= '<th>'.$key.'</th>';
                                    }
                                    $str1 .= "</tr></thead>";
                                $tb .= $str1.$str2."</table>";
                                $response = ['status' => 'success', 'custreport' => $tb, 'type' => $type, 'tid' => $id, 'message' => 'customer report generated','statusCode' => Response::HTTP_OK];
                    return response()->json(['response'=> $response]);

                        }else{
                            $returnResponse = ['status' => 'Failed', 'statusCode' => Response::HTTP_BAD_REQUEST];
                            return response()->json(['response'=> $returnResponse]);    
                        }
                    }
    			}
    			$custData = DB::select($data);
    			foreach ($custData as $key => $value) {
				$custData[$key] = (array)$value;
    				foreach ($value as $newkey => $newvalue){
					if($newkey == 'veh_last_location_updated_at'){
						if($newvalue == null || $newvalue == ''){
    							$custData[$key]['VehicleLocation'] = '-';
    						}else{
    							$custData[$key]['VehicleLocation'] = $newvalue;
    						}
    						//$custData[$key]['VehicleLocation'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'android_notification_token'){
    						if($newvalue == '' || $newvalue=null){
    							$custData[$key]['Android'] = 'No';
    						}else{
    							$custData[$key]['Android'] = 'Yes';
    						}
    						unset($custData[$key][$newkey]);
    					}
					if($newkey == 'op_razoarpay_acc_id'){
    						$custData[$key]['RazorpayId'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_email'){
    						$custData[$key]['PartnerEmail'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_address_state'){
    						$opState = StatesMaster::select('state')->where('id',$newvalue)->get()->first();
    						if($opState){
    							$custData[$key]['AddressState'] = $opState->state;
    						}else{
    							$custData[$key]['AddressState'] = '-';
    						}
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_address_city'){
    						$opCity = CitiesMaster::select('city')->where('id',$newvalue)->get()->first();
    						if($opCity){
    							$custData[$key]['AddressCity'] = $opCity->city;
    						}else{
    							$custData[$key]['AddressCity'] = '-';
    						}
    						unset($custData[$key][$newkey]);	
    					}
						if($newkey == 'op_address_pin_code'){
							$opPincode = PincodesMaster::select('pincode')->where('id',$newvalue)->get()->first();
    						if($opPincode){
    							$custData[$key]['AddressPin'] = $opPincode->pincode;
    						}else{
    							$custData[$key]['AddressPin'] = '-';
    						}
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_type_id'){
    						if($newvalue == '1'){
    							$custData[$key]['OperatorType'] = 'Individual';
    						}else{
    							$custData[$key]['OperatorType'] = 'Business';
    						}
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'veh_city'){
    						$custData[$key]['BaseStation'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_bank_name'){	
    						$bankName = BankMaster::select('op_bank_name')->where('id',$newvalue)->get()->first();
    						if($bankName){
								$custData[$key]['BankDetails'] = $bankName->op_bank_name;
							}else{
								$custData[$key]['BankDetails'] = '-';
							}
							unset($custData[$key][$newkey]);
						}		
						if($newkey == 'op_bank_ifsc'){	
							if(isset($newvalue)){
								$custData[$key]['BankDetails'] = $custData[$key]['BankDetails'].','.$newvalue;
							}else{
								$custData[$key]['BankDetails'] = '-';
							}
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'op_bank_account_number'){	
							if(isset($newvalue)){
								$custData[$key]['BankDetails'] = $custData[$key]['BankDetails'].','.$newvalue;
							}else{
								$custData[$key]['BankDetails'] = '-';
							}
							unset($custData[$key][$newkey]);
						}
    					if($newkey == 'op_is_verified'){
    						$custData[$key]['Verified'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'veh_registration_no'){
    						$custData[$key]['VehicleRegistration'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'veh_make_model_type'){
    						$custData[$key]['Make'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'veh_model_name'){
    						$model = Vehicles::where('veh_id', $newvalue)->value('veh_model_name');
    						$custData[$key]['Model'] = $model;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'op_uid'){
    						$custData[$key]['UID'] = $newvalue;
    						unset($custData[$key][$newkey]);
    					}
    					if($newkey == 'user_uid'){	
							$custData[$key]['CID'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
    					if($newkey == 'address_pin_code'){	
							$custData[$key]['AddressPin'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'email'){	
							$custData[$key]['Email'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'user_dob'){	
							$custData[$key]['DateOfBirth'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'address_city'){	
							$custCity = CitiesMaster::select('city')->where('id',$newvalue)->get()->first();
    						if($custCity){
    							$custData[$key]['AddressCity'] = $custCity->city;
    						}else{
    							$custData[$key]['AddressCity'] = '-';
    						}
							//$custData[$key]['AddressCity'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'address_state'){	
							$custState = StatesMaster::select('state')->where('id',$newvalue)->get()->first();
    						if($custState){
    							$custData[$key]['AddressState'] = $custState->state;
    						}else{
    							$custData[$key]['AddressState'] = '-';
    						}
							//$custData[$key]['AddressState'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'created_at'){	
							$custData[$key]['CreatedAt'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
    					if($newkey == 'trip_transaction_id'){	
							$custData[$key]['TripId'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'book_date'){	
							$custData[$key]['TripDateTime'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'base_amount'){	
							$custData[$key]['PartnerCharge'] = $newvalue + ($value->op_adjustment);
							//unset($custData[$key]['op_adjustment']);
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'actual_amount'){	
							//$custData[$key]['TripTotal'] = $newvalue;
							$custData[$key]['TripTotal'] = $newvalue + ($value->user_adjustment);
							//unset($custData[$key]['user_adjustment']);
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'ggt_factor'){	
							$custData[$key]['GGTFactor'] = $newvalue + ($value->ggt_adjustment);
							//unset($custData[$key]['ggt_adjustment']);
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'start_address_line_1'){	
							$custData[$key]['Pickup'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'intermediate_address'){
							$dropData = json_decode($newvalue,true);
							/*$droplocations='';
							foreach ($dropData as $key => $value) {
								$droplocations = $droplocations.','.$value['dest_address_line_1'];
							}*/
							$custData[$key]['Drop'] = $dropData[0]['dest_address_line_1'];
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'weight'){	
							$custData[$key]['Weight'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'material_type'){
                            $custData[$key]['Material'] = $newvalue;
                            unset($custData[$key][$newkey]);
                        }
						if($newkey == 'pay_order_id'){
							if(isset($newvalue)){
							$getPayData = UserPayments::select('user_order_transaction_id')->where('user_order_id',$newvalue)->get()->first();	
							$custData[$key]['TransactionId'] = $getPayData->user_order_transaction_id;
							}else{
							$custData[$key]['TransactionId'] = '-';
							}
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'bill_details'){
							if(isset($newvalue)){
								$invoiceData = json_decode($newvalue,true);
								$invoiceNo = $invoiceData['invoiceNumber']; 
								$custData[$key]['InvoiceNumber'] = $invoiceNo;
							}else{
								$custData[$key]['InvoiceNumber'] = '-';
							}	
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'is_bid'){
							if($newvalue == '0'){
								$custData[$key]['Enquiry/Bid'] = 'Enquiry';
							}else{
								$custData[$key]['Enquiry/Bid'] = 'Bid';
							}	
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'user_first_name'){	
							$custData[$key]['CustomerName'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'user_last_name'){	
							$custData[$key]['CustomerName'] = $custData[$key]['CustomerName'].' '.$newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'op_first_name'){	
							$custData[$key]['PartnerName'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'op_last_name'){	
							$custData[$key]['PartnerName'] = $custData[$key]['PartnerName'].' '.$newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'user_mobile_no'){	
							$custData[$key]['CustomerMobile'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
						if($newkey == 'op_mobile_no'){	
							$custData[$key]['PartnerMobile'] = $newvalue;
							unset($custData[$key][$newkey]);
						}
    				}
    			}
    			//end here
    			$custData_array = array();
    			if($custData){
    				$tb = '<table id="'.$id.'" class="table table-list" data-page-length="25">';    				
    				$str2 = '<tbody>';
    				foreach($custData as $key => $value){
    					$str2 .= "<tr>";
    					foreach ($value as $key1 => $value1)
    					{
    						$custData_array[$key1][] = $value1;
    						$str2 .= '<td>'.$value1.'</td>';
    					}
    					$str2 .= "</tr>";
				    }
				    $str2 .= '</tbody>';
    				
    				$str1 = '<thead><tr>';
				    foreach($custData_array as $key => $value)
				    {
				    	$str1 .= '<th>'.$key.'</th>';
				    }
				    $str1 .= "</tr></thead>";
    				$tb .= $str1.$str2."</table>";
    				$response = ['status' => 'success', 'custreport' => $tb, 'type' => $type, 'tid' => $id, 'message' => 'customer report generated','statusCode' => Response::HTTP_OK];
                    return response()->json(['response'=> $response]);
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
}
