<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Operator;
use Config;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Ses\SesClient;


class SendMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        if (! Gate::allows('mail_create'))
        {
            return abort(401);
        }
        else{

            $operators_emails = Operator::select('op_email')->whereNull('deleted_at')->get();
                $header = "All Operators Emails";

            return view('admin.information.mail.create', ['operators_emails' => $operators_emails, 'header' => $header]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('mail_create'))
        {
            return abort(401);
        }
        else{
            $email_Array = $request->selected_mails;
            $email_Subject = $request->subject_to;
            $email_Body = $request->mail_message;
            $send_email = $this->sendEmailTo($email_Array, $email_Subject, $email_Body);
            return redirect()->route('information.index')->with('success', 'Email send successfully.');
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

    //mail function
    public function sendEmailTo($emailArray, $subject, $message)
    {
        if (! Gate::allows('information_manage'))
        {
            return abort(401);
        }
        else{  
            $credentials = array(
                "region" => 'us-east-1',
                'version' => 'latest'
            );
            
             
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
                                     'Data'  => $message,
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
    }
}
