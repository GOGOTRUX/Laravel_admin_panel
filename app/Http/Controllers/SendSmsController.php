<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

class SendSmsController extends Controller
{
    public function __construct()
    {
        $this->aws = new CustomAwsController; 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $redirectTo = '/information';

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // test send sms
        $postdata = $request->all();
        if(!empty($postdata)){
            $file = $request->file('excelupload');
            $destinationPath = 'sms_excel'; //Folder where we want to upload the file (inside public folder)
            $filename = $file->getClientOriginalName(); //filename original
            $filename = rand(0, 999999999).preg_replace('/\s+/', '', $filename);
            $upload_success = $request->file('excelupload')->move($destinationPath, $filename);
            $path = $destinationPath.'/'.$filename;
            
            //maat excel file uploading
            $data = Excel::load($path)->get();
            $firstrow = $data->first()->toArray();
            if(isset($firstrow['mobile_numbers'])){
                $data = $data->all();
                foreach ($data as $key => $value) {
                    $country_code = '+91';
                    $mobile_no = (int)$data[$key]->mobile_numbers;
                    $mobile_no = $country_code.$mobile_no;
                    $app_link = 'https://partners.gogotrux.com/#/';
                    $otp_message = $request->sms_message; //.' '.$app_link;
                    
                    $otp = $this->aws->sendSmsOTP($mobile_no,$otp_message);
                } 
                unlink($upload_success);
                return redirect()->route('information.index')->with('success', 'Sms send successfully.'); 
            }else{
                unlink($upload_success);
                return redirect()->route('information.index')->with('error', 'Please upload valid excel format.'); 
            }     
        }else{
            Log::warning("empty request:SendSmsController");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
