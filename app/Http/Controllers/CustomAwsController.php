<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Exception;
use Config;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use DB;
use Aws\Sns\SnsClient;
use Aws\Ses\SesClient;
use SMSG;
use App\Models\CustomerBookTrip;
use App\Models\UserPayments;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\BankMaster;
use App\Models\CitiesMaster;
use Log;

class CustomAwsController extends Controller
{
    //user upload code added by madhuri 
    public function downloadUserFromS3($url, $path)
    {
        $downloaded_from_s3 = 0;
	//$s3 = new S3Client(['region' => 'us-east-1','version' => 'latest']);
	$s3 = new S3Client([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
            'key'=> Config::get('custom_config_file.key'),
            'secret'=>Config::get('custom_config_file.secret'),
            ],
	]);

        try
        {
            $s3_bucket_array = explode('/',$url);
            // dd($s3_bucket_array);
            $ar_count = count($s3_bucket_array);
            if(!empty($s3_bucket_array) && $ar_count > 3) { // && count($s3_bucket_array > 3)
                $bucketname = $s3_bucket_array[3];
                $ar_count = count($s3_bucket_array);
                $key = '';
                $key = $s3_bucket_array[4];
                // for($i = 1; $i < $ar_count; $i++){
                //  if($i == 1){ 
                //      $key .= $s3_bucket_array[$i];
                //  }
                //  else
                //  {
                //      $key .= "/".$s3_bucket_array[$i];
                //  }
                // }
                
                $filename_array = explode('/', $key);
                $filename = $filename_array[count($filename_array) - 1];
                // echo 'filename'.$filename;
                // echo '<br>';
                // echo 'bu'.$bucketname.'<br>';
                // echo 'key'.$key.'<br>';
                // echo 'save as'.$path.$filename.'<br>';
                $result = $s3->getObject(
                    array(
                        'Bucket' => $bucketname,
                        'Key' => $key,
                        'SaveAs' => $path.$filename
                    )
                );
                $downloaded_from_s3 = $filename;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage() . "\n";
        }
        
        return $downloaded_from_s3;
    }

    public function uploadUserToS3($key, $path, $bucketname)
    {
        $uploaded_to_s3 = 0;
        $dir = Config::get('custom_config_file.upload-user-image-folder');
	//$s3 = new S3Client(['region' => 'us-east-1','version' => 'latest']);
	$s3 = new S3Client([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
            'key'=> Config::get('custom_config_file.key'),
            'secret'=>Config::get('custom_config_file.secret'),
            ],
        ]);
        try
        {
            $result = $s3->putObject(
                array(
                    'Bucket' => $bucketname,
                    'Key' =>  $key,
                    'SourceFile' => $path
                )
            );
            $uploaded_to_s3 = $bucketname.'/'.$key;
        }
        catch(Exception $e)
        {
            echo $e->getMessage() . "\n";
        }  
              
        return $uploaded_to_s3;
    }
    //user upload code end by madhuri 

    public function uploadToS3($key, $path, $bucketname)
    {
        $uploaded_to_s3 = 0;
        $dir = Config::get('custom_config_file.upload-image-folder');
	//  $s3 = new S3Client(['region' => 'us-east-1','version' => 'latest']);
	$s3 = new S3Client([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
            'key'=> Config::get('custom_config_file.key'),
            'secret'=>Config::get('custom_config_file.secret'),
            ],
	]);

        try
        {
            $result = $s3->putObject(
                array(
                    'Bucket' => $bucketname,
                    'Key' =>  $dir.$key,
                    'SourceFile' => $path
                )
            );
            $uploaded_to_s3 = $bucketname.'/'.$key;
        }
        catch(Exception $e)
        {
            echo $e->getMessage() . "\n";
        }  
              
        return $uploaded_to_s3;
    }

    public function uploadToPublicS3($key, $path, $bucketname)
    {
        $uploaded_to_s3 = 0;
        $dir = Config::get('custom_config_file.upload-image-folder');
	//$s3 = new S3Client(['region' => 'us-east-1','version' => 'latest']);
	$s3 = new S3Client([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
            'key'=> Config::get('custom_config_file.key'),
            'secret'=>Config::get('custom_config_file.secret'),
            ],
        ]);
        
        try
        {
            $result = $s3->putObject(
                array(
                    'Bucket' => $bucketname,
                    'Key' =>  $key,
                    'SourceFile' => $path,
                    'ACL' => 'public-read',
                )
            );
            $uploaded_to_s3 = $bucketname.'/'.$key;
        }

        catch(Exception $e)
        {
            echo $e->getMessage() . "\n";
        }  
              
        return $uploaded_to_s3;
    }

    public function downloadFromS3($url, $path)
    {
        $downloaded_from_s3 = 0;
	//$s3 = new S3Client(['region' => 'us-east-1','version' => 'latest']);
	$s3 = new S3Client([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
            'key'=> Config::get('custom_config_file.key'),
            'secret'=>Config::get('custom_config_file.secret'),
            ],
        ]);
        try
        {
            $s3_bucket_array = explode('/',$url);
            // dd($s3_bucket_array);
            $ar_count = count($s3_bucket_array);
            if(!empty($s3_bucket_array) && $ar_count > 3) { // && count($s3_bucket_array > 3)
                $bucketname = $s3_bucket_array[3];
                $ar_count = count($s3_bucket_array);
                $key = '';
                $key = $s3_bucket_array[4];
                // for($i = 1; $i < $ar_count; $i++){
                //  if($i == 1){ 
                //      $key .= $s3_bucket_array[$i];
                //  }
                //  else
                //  {
                //      $key .= "/".$s3_bucket_array[$i];
                //  }
                // }
                
                $filename_array = explode('/', $key);
                $filename = $filename_array[count($filename_array) - 1];
                // echo 'filename'.$filename;
                // echo '<br>';
                // echo 'bu'.$bucketname.'<br>';
                // echo 'key'.$key.'<br>';
                // echo 'save as'.$path.$filename.'<br>';
                $result = $s3->getObject(
                    array(
                        'Bucket' => $bucketname,
                        'Key' => $key,
                        'SaveAs' => $path.$filename
                    )
                );
                $downloaded_from_s3 = $filename;
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage() . "\n";
        }
        
        return $downloaded_from_s3;
    }

    //function to delete image from s3
    public function deleteFileFromS3($url) {
        $s3_bucket_array = explode('/',$url);
        $ar_count = count($s3_bucket_array);
        if(!empty($s3_bucket_array) && $ar_count > 3) 
        {
            $bucketname = $s3_bucket_array[3]; //bucketname
            $key = '';
            $key = $s3_bucket_array[4]; //key
            $filename_array = explode('/', $key);
            $filename = $filename_array[count($filename_array) - 1];  //filename
            
            $s3 = S3Client::factory(array(
                'version' => 'latest',
                'region' => 'us-east-1'
            ));
            try
            {
                $result = $s3->deleteObject(['Bucket' => $bucketname, 'Key' => $key]);
                return true;
            }
            catch(Exception1 $e)
            {
                return false;
            }
        }
    }

    function sendSmsOTP($mobile_no, $otp_message)
    {
        /*try {
            $sns = SnsClient::factory(array(
                        // 'credentials' => array(
                        //  'key' => Config::get('custom_config_file.sns_key'),
                        //  'secret' => Config::get('custom_config_file.sns_secret')
                        // ),
                        'region' => 'us-east-1',
                        'version' => 'latest'
                    ));
            $msgattributes = [
                        'AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'GoGoTrux',
                        ],
                        'AWS.SNS.SMS.SMSType' => [
                            'DataType' => 'String',
                            'StringValue' => 'Transactional',
                        ]
                    ];
            $payload = array(
                    'Message' => $otp_message,
                    'PhoneNumber' => $mobile_no,
                    'MessageAttributes' => $msgattributes,
                    'Subject'=>'GoGoTrux'
                );

            $result = $sns->publish($payload);
            return $result;
        } catch (Exception $e) {
            report($e);

            return false;
        }*/

    /*    try{
            $mobile = $mobile_no;
            $sender = 'GGTRUX';
            $msg = $otp_message;
            $provider = 'msg91';
            $route = '4';
            // Will also catch the output
            $result = SMSG::send($mobile, $sender, $msg, $provider, $route);
            return $result;
        }catch(Exception $e){
            report($e);
            return false;
        }*/
	$get_active_sms_gateway = Setting::select('active_sms_gateway')->where('id',5)->first();
        if($get_active_sms_gateway->active_sms_gateway == 'MSG91'){
            try{
                $mobile = $mobile_no;
                $sender = 'GoGoTx';
                $msg = $otp_message;
                $provider = 'msg91';
                $route = '4';
                // Will also catch the output
                $result = SMSG::send($mobile, $sender, $msg, $provider, $route);
                return $result;
            }catch(Exception $e){
                report($e);
                return false;
            }
        }else{
            //net core sms gateway
            try{
                $msg = str_replace(' ','%20',$otp_message);
                $url ='http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=379528&senderid=&username=7350920881&password=GGT%40smsapi20&time=&tz=&jobname=&async=&tokenkey=&ssl=&short=&To='.$mobile_no.'&Text='.$msg.'&Ok=Go';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec ($ch);
                $err = curl_error($ch);  //if you need
                curl_close ($ch);
                if ($err) {
                    Log::warning( $err);
                    return null;
                } else {
                    Log::warning( $response);
                    return $response;
                }
            }catch(Exception $e){
                report($e);
                return false;
            }
        }
    }

    public function sendEmailTo($emailArray, $subject, $message, $type)
    {   
        $credentials = array(
            "region" => 'us-east-1',
            'version' => 'latest'
        );
        
        if($type=='subplan_approved') {
            $beautified_email_body = $this->beautifyapprovedSubplanEmail($message);
        }else if($type=='subplan_op_expiry'){
            $beautified_email_body = $this->beautifyexpiryOpSubplanEmail($message);
        }
        else if($type=='subplan_expiry'){
            $beautified_email_body = $this->beautifyexpirySubplanEmail($message);
        }
        else if($type=='generate-trip-bill'){
           // $beautified_email_body = $this->beautifyGenerateTripBillEmail($message);
	$beautified_email_body = $this->getInvoiceContent($message);
        }
        else{
            $beautified_email_body = 'test email';
        }

        $client = SesClient::factory($credentials);
        try{
            $result = $client->sendEmail(
                array(
                    'Source' => config('custom_config_file.Source'),
                    'Destination' => array(
                        'ToAddresses' => $emailArray
                    ),
                    'Message' => array(
                        'Subject' => array(
                            'Data' => $subject
                        ),
                        'Body' => array(
                            'Html' => array(
                                // 'Data'  => 'This is a <b>test email</b>.',
                                'Data' => $beautified_email_body,
                            )
                        ),
                    )
                )
            );
            return $result;
        }catch(Exception $e){
            echo $e;
            return -1;
        }
    }

    public function beautifyapprovedSubplanEmail($message)
    {
        
        if(!empty($message)){
            $created_by = $message['created_by'];
            $plan_name = $message['plan_name'];
            $wheel_type = $message['wheel_type'];
        }else{
            $created_by = 'User';
            $plan_name = '-';
            $wheel_type = '-';
        }

        return '<html><head><meta name="x-apple-disable-message-reformatting" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body  data-gr-c-s-loaded="true"><div style="margin:0px;"><div style="background-color:#ddd;padding:10px;"><div style="text-align:center;width:100%;padding:6px 0px;"><img src="http://beta-driver.gogotrux.com/assets/images/gogotrux-logo.png" alt="GoGoTrux" height="40"><hr style="background-color:#000;height:1px;"></div><div><div style="background-color: #fff;border-radius:6px;width:60%;margin:10px auto;text-align:center;padding:10px;"><h3 style="margin:10px 0;color:#FF9800;font-size:22px;">Congratulation</h3><span style="color: #E91E63;">Congratulation '.$created_by.' your subscription plan '.$plan_name.' for '.$wheel_type.' wheeler has been approved by Admin</span><p>Please complete the Profile page to get empanelled on the Best Mini Truck Ordering App.</p><hr style="margin:10px 0px;"><h3 style="margin:10px 0;color:#FF9800;font-size:22px;">अभिनंदन!</h3><span style="color: #E91E63;">GoGoTrux<sup style="font-size:8px;">TM</sup> App वर साइन अप करण्यासाठी '.$created_by.' अभिनंदन!</span><p>सर्वोत्कृष्ट मिनी ट्रक ऑर्डरिंग अॅपवर पॅनेल मिळविण्यासाठी कृपया प्रोफाइल पृष्ठ पूर्ण करा.</p><div style="margin-top:15%;text-align:left;"><p style="color:#333;font-size:14px;">Team GoGoTrux<sup style="font-size:8px;">TM</sup></p></div></div></div></div></div></body></html>';
    }

    public function beautifyexpiryOpSubplanEmail($message){
        if(!empty($message)){
            $days = $message['days'];
            $plan_name = $message['plan_name'];
            $wheel_type = $message['wheel_type'];
        }else{
            $days = 'User';
            $plan_name = '-';
            $wheel_type = '-';
        }

        return '<html><head><meta name="x-apple-disable-message-reformatting" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body  data-gr-c-s-loaded="true"><div style="margin:0px;"><div style="background-color:#ddd;padding:10px;"><div style="text-align:center;width:100%;padding:6px 0px;"><img src="http://beta-driver.gogotrux.com/assets/images/gogotrux-logo.png" alt="GoGoTrux" height="40"><hr style="background-color:#000;height:1px;"></div><div><div style="background-color: #fff;border-radius:6px;width:60%;margin:10px auto;text-align:center;padding:10px;"><h3 style="margin:10px 0;color:#FF9800;font-size:22px;">Alert</h3><span style="color: #E91E63;">Hello, <br> your subscription plan '.$plan_name.' for '.$wheel_type.' wheeler is going to expire in '.$days.'days </span><p>Please by a new plan to get benefits of maximum trip booking.</p><hr style="margin:10px 0px;"><div style="margin-top:15%;text-align:left;"><p style="color:#333;font-size:14px;">Team GoGoTrux<sup style="font-size:8px;">TM</sup></p></div></div></div></div></div></body></html>';
    }

    public function beautifyexpirySubplanEmail($message){
        if(!empty($message)){
            $days = $message['days'];
            $plan_name = $message['plan_name'];
            $wheel_type = $message['wheel_type'];
        }else{
            $days = 'User';
            $plan_name = '-';
            $wheel_type = '-';
        }

        return '<html><head><meta name="x-apple-disable-message-reformatting" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body  data-gr-c-s-loaded="true"><div style="margin:0px;"><div style="background-color:#ddd;padding:10px;"><div style="text-align:center;width:100%;padding:6px 0px;"><img src="http://beta-driver.gogotrux.com/assets/images/gogotrux-logo.png" alt="GoGoTrux" height="40"><hr style="background-color:#000;height:1px;"></div><div><div style="background-color: #fff;border-radius:6px;width:60%;margin:10px auto;text-align:center;padding:10px;"><h3 style="margin:10px 0;color:#FF9800;font-size:22px;">Alert</h3><span style="color: #E91E63;">Hello Admin, <br> your subscription plan '.$plan_name.' for '.$wheel_type.' wheeler is going to expire in '.$days.'days </span><hr style="margin:10px 0px;"><div style="margin-top:15%;text-align:left;"><p style="color:#333;font-size:14px;">Team GoGoTrux<sup style="font-size:8px;">TM</sup></p></div></div></div></div></div></body></html>';
    }

    //beautify email
    public function beautifyGenerateTripBillEmail($message){
        $getTripData = CustomerBookTrip::join('ggt_user_payments','ggt_user_payments.user_order_id','=','ggt_user_book_trip.pay_order_id')->join('ggt_operator_users','ggt_operator_users.op_user_id','=','ggt_user_book_trip.op_id')->select('start_address_line_1','book_date','dest_address_line_1','material_type','weight','actual_amount','cust_invoice_no','trip_transaction_id','payment_type','user_order_transaction_id','is_paylink_send','user_order_paylink_id','op_first_name','op_last_name','ggt_user_book_trip.created_at','user_adjustment')->where('ggt_user_book_trip.trip_transaction_id',$message['booking_details']['trip_transaction_id'])->first();
        if(!empty($message['booking_details']['cust_name'])){
            $firstname = $message['booking_details']['cust_name'];
        }else{
            $firstname = 'User';
        }

	$tripDateTime = explode(' ', $getTripData->book_date);
        $tripDate = $tripDateTime[0];
        $triptime = $tripDateTime[1];

        $bookDateTime = explode(' ', $getTripData->created_at);
        $bookDate = $bookDateTime[0];
        $booktime = $bookDateTime[1];

	if(!isset($getTripData->user_order_transaction_id)){
            if($getTripData->is_paylink_send == 1){
                $getTripData->user_order_transaction_id = $getTripData->user_order_paylink_id;
            }
        }
	$getTripData->actual_amount = $getTripData->actual_amount + ($getTripData->user_adjustment);
        $email_message = '';

	if(isset($message['gstn'])){
            $gstn = $message['gstn'];
        }else{
            $gstn = '-';
        }
	$currentDate = Carbon::now()->toDateTimeString();
        /*$email_message = '<html><head><meta name="x-apple-disable-message-reformatting" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body  data-gr-c-s-loaded="true"><div style="margin:0px;"><div style="background-color:#ddd;padding:10px;"><div style="text-align:center;width:100%;padding:6px 0px;"><img src="http://admin.gogotrux.com/images/gogotrux-logo.png" alt="GoGoTrux" height="40"><hr style="background-color:#000;height:1px;"></div><div><div style="background-color: #fff;border-radius:6px;width:80%;margin:10px auto;padding:10px;"><div><p><b>FortSatt Business Technologies Pvt. Ltd.</b><br>Vedanta House, 1st Floor, Plot No 6, <br>Tejeswani 1, Near Medipoint Hospital, <br>Aundh, Pune, 411 007<br>CIN: U72900PN2018PTC178466</p></div> <hr style="margin:10px 0px;"><div style="width:100%;padding:6px 10px;"><table class="table table-border"><thead><tr><th colspan="2">Invoice No : '.$getTripData->cust_invoice_no.'</th></tr></thead><tbody><tr><td>Name</td><td>'.$message['booking_details']['cust_name'].'</td></tr><tr><td>Contact</td><td>'.$message['booking_details']['cust_mobile'].'</td></tr><tr><td>Book Date</td><td>'.$getTripData->book_date.'</td></tr><tr><td>Pickup</td><td>'.$getTripData->start_address_line_1.'</td></tr><tr><td>Delivery</td><td>'.$getTripData->dest_address_line_1.'</td></tr><tr><td>Material Type</td><td>'.$getTripData->material_type.'</td></tr><tr><td>Weight Kg</td><td>'.$getTripData->weight.'</td></tr><tr><td>Payment Mode</td><td>'.$getTripData->payment_mode.'</td></tr><tr><td>TransactionId</td><td>'.$getTripData->user_order_transaction_id.'</td></tr></tbody><tfoot><tr><td colspan="2">Total Amount: '.$getTripData->actual_amount.'</td></tr></tfoot></table></div><div style="margin-top:15%;text-align:center;"><p style="color:#333;font-size:10px;"><b>Thank You for using GOGOTRUX Services. Login to GOGOTRUX.COM</b><br>Billing Queries:  7350920881:Email: gogotrux@gmail.com: New Trip Booking 70305 70500/70305 80500: www.gogotrux.com <br>GOGOTRUXTM  is owned by FortSatt Business Technologies Pvt. Ltd, a Registered Startup under DIPP, Govt. of India. Exempt from GST payment.</p></div></div></div></div></div></body></html>';*/
	$email_message = '<html><head><meta name="x-apple-disable-message-reformatting" /><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body  data-gr-c-s-loaded="true"><div style="margin:0px;"><div style="background-color:#ddd;padding:10px;"><div style="text-align:center;width:100%;padding:6px 0px;"><img src="http://admin.gogotrux.com/images/gogotrux-logo.png" alt="GoGoTrux" height="40"><hr style="background-color:#000;height:1px;"></div><div><div style="background-color: #fff;border-radius:6px;width:90%;margin:10px auto;padding:10px;"><div><p><b>FortSatt Business Technologies Pvt. Ltd.</b><br>Vedanta House, 1st Floor, Plot No 6, <br>Tejeswani 1, Near Medipoint Hospital, <br>Aundh, Pune, 411 007</p></div> <hr style="margin:10px 0px;"><div style="width:100%;padding:6px 10px;"><table class="table table-border"><thead></thead><tbody><tr><td><b>CustomerName :</b></td><td>'.$message['booking_details']['cust_name'].'</td></tr><tr><td><b>Invoice No. :</b></td><td>'.$getTripData->cust_invoice_no.'</td><td><b>Invoice Date :</b></td><td>'.$currentDate.'</td></tr><tr><td><b>Mobile No. :</b></td><td>'.$message['booking_details']['cust_mobile'].'</td><td><b>GSTN</b></td><td>'.$gstn.'</td></tr><tr><td><b>Trip Date :</b></td><td>'.$tripDate.'</td><td><b>Trip Time :</b></td><td>'.$triptime.'</td></tr><tr><td><b>Book Date :</b></td><td>'.$bookDate.'</td><td><b>Book Time :</b></td><td>'.$booktime.'</td></tr><tr><td><b>TripId :</b></td><td>'.$getTripData->trip_transaction_id.'</td></tr><tr><td><b>Pickup :</b></td><td>'.$getTripData->start_address_line_1.'</td></tr><tr><td><b>Delivery :</b></td><td>'.$getTripData->dest_address_line_1.'</td></tr><tr><td><b>Material Type :</b></td><td>'.$getTripData->material_type.'</td><td><b>Weight Kg :</b></td><td>'.$getTripData->weight.'</td></tr><tr><td><b>Payment Mode :</b></td><td>'.$getTripData->payment_type.'</td><td><b>TransactionId :</b></td><td>'.$getTripData->user_order_transaction_id.'</td></tr><tr><td><b>Service Partner :</b></td><td>'.$getTripData->op_first_name.' '.$getTripData->op_last_name.'</td></tr></tbody><div style="width:100%;padding:6px 10px;"><tfoot><tr><td colspan="2"><b>Total Amount Rs.:</b> '.$getTripData->actual_amount.'</td></tr></tfoot></table></div><div style="margin-top:15%;text-align:center;"><p style="color:#333;font-size:12px;"><b>Thank You for using GOGOTRUX Services. Logon to GOGOTRUX.COM</b><br>Billing Queries:  7350920881:Email: gogotrux@gmail.com: New Trip Booking 70305 70500/70305 80500: www.gogotrux.com <br>GOGOTRUXTM  is owned by FortSatt Business Technologies Pvt. Ltd, a Registered Startup under DIPP, Govt. of India. Exempt from GST payment.</p></div></div></div></div></div></body></html>';
        return $email_message;
    }

    public function getInvoiceContent($message){
        
        $trip_transaction_id = $message['booking_details']['trip_transaction_id'];
	$gstin = $message['gstn'];
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
            $email_message = '<html>
            <head>
                <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1">
                <style>
                    p{
                        margin: 0px;
                    }
                </style>  
            </head>
            <body>
                <div style="border: 1px solid;
                margin: 10px;
                padding: 10px;">
                    <div>
                        <div>
                            <table style="width:100%;border-bottom: 2px solid black; margin-top: 50px;margin-bottom: 10px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div style="text-align:center">
                                                <p style="color:#FF8000"><b><u>Invoice</u></b></p>
                                                <p><b>FortSatt Business Technologies Pvt. Ltd.</b></p>
                                                <p>Vedanta House, 1st Floor, Plot No 6, Tejeswani 1, Near Medipoint Hospital, Aundh, Pune, 411007</p>
                                                <p><small><b>CIN:</b> U72900PN2018PTC178466</small></p>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <table border="1" style="margin-bottom: 10px;">
                                                    <tr>
                                                      <th colspan="4">Bank Account Detail</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="padding:4px;">Account Name</th>
                                                        <td style="padding:4px;" colspan="3">FortSatt Business Technologies Pvt. Ltd</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="padding:4px;">Bank</th>
                                                        <td style="padding:4px;">State Bank of India</td>
                                                        <th style="padding:4px;">Branch</th>
                                                        <td style="padding:4px;">PBB Aundh, Pune</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="padding:4px;">Account No</th>
                                                        <td style="padding:4px;">38527863582 </td>
                                                        <th style="padding:4px;">IFSC code:</th>
                                                        <td style="padding:4px;">SBIN0015707</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
                                <tbody>
                                    <tr>
                                        <td><b>Customer Name</b></td>
                                        <td colspan="3">'.$trip_details->user_first_name.'</td>
                                        <td><b>Invoice No:</b></td>
                                        <td>'.$trip_details->cust_invoice_no.'</td>
                                        <td><b>Invoice Date</b></td>
                                        <td>'.$trip_details->updated_at.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Address</b></td>
                                        <td colspan="3">'.$trip_details->cust_address.'</td>
                                        <td><b>GSTIN</b></td>
                                        <td>'.$gstin.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Contact Name</b></td>
                                        <td>'.$trip_details->user_first_name.'</td>
                                        <td><b>Mobile No</b></td>
                                        <td>'.$trip_details->user_mobile_no.'</td>
                                        <td><b>Email</b></td>
                                        <td colspan="3">'.$trip_details->email.'</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
                                <tbody>
                                    <tr>
                                        <td><b>Transaction ID</b></td>
                                        <td>'.$trip_details->user_order_transaction_id.'</td>
                                        <td><b>Trip ID</b></td>
                                        <td>'.$trip_details->trip_transaction_id.'</td>
                                        <td><b>Service Partner</b></td>
                                        <td colspan="3">'.$trip_details->op_first_name.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Trip Date</b></td>
                                        <td>'.$trip_details->trip_date.'</td>
                                        <td><b>Trip Hour</b></td>
                                        <td>'.$trip_details->trip_time.'</td>
                                        <td><b>Booking Date</b></td>
                                        <td>'.$trip_details->book_date.'</td>
                                        <td><b>Booking Time</b></td>
                                        <td>'.$trip_details->book_time.'</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Payment Mode</b></td>
                                        <td colspan="3">'.$trip_details->payment_type.'</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;border-bottom: 2px solid black;margin-top: 10px;margin-bottom: 10px;">
                                <tbody>
                                    <tr>
                                        <td><b>Pickup Address</b></td>
                                        <td colspan="9">'.$trip_details->start_address_line_1.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Delivery Address</b></td>
                                        <td colspan="9">'.$trip_details->dest_address_line_1.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Material Type</b></td>
                                        <td>'.$trip_details->material_type.'</td>
                                        <td><b>Material Weight Kg</b></td>
                                        <td>'.$trip_details->weight.'</td>
                                    </tr>
				        <tr>
                                    <td><b>Incidental Charge</b></td>
                                    <td>'.$trip_details->incidental_charges.'</td>
                                    <td><b>Other Charge</b></td>
                                    <td>'.$trip_details->other_charges.'</td>
                                    </tr>
                                    <tr>
                                        <td><b>Distance(kms)</b></td>
                                        <td>'.$trip_details->total_distance.'</td>
                                        <td><b>Amount (Rupees)</b></td>
                                        <td>'.$trip_details->actual_amount.'</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td><td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;margin-top: 10px;">
                                <tbody>
                                    <tr>
                                        <td><b>Total Amount(in Words)</b></td>
                                        <td>'.$trip_details->amount_in_words_cust.'</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="width:100%;margin-top: 20px;">
                                <tbody>
                                    <tr>
                                        <td colspan="10" align="center">
                                            <p style="font-size: 11px;"><b>Thank You for using GOGOTRUX Services. Logon to GOGOTRUX.COM</b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10" align="center">
                                            <p style="font-size: 13px;">Billing Queries: 7350920881:Email: gogotrux@gmail.com: New Trip Booking 70305 70500/70305 80500: www.gogotrux.com</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="10" align="center">
                                            <p style="font-size: 9px;">GOGOTRUX<sup>TM</sup> is owned by FortSatt Business Technologies Pvt. Ltd, a Registered Startup under DIPP, Govt. of India. Exempt from GST payment.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>	
                </div>	
            </body>
            </html>';    
            return $email_message;
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

