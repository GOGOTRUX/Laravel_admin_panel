<?php

namespace App\Http\Controllers;

use App\Models\GetMapAccessToken;
use DB;
use Illuminate\Http\Request;


class TestGuzzleController extends Controller
{
     //route in web.php 
    function callCreate(){
        try {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://outpost.mapmyindia.com/api/security/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id'=> '33OkryzDZsIv-0ItH_Z5UNahPSK5lioJCcwS1EHG-N4jV0GYSfP9wkajZyi5OJZpLFzje3em2cezmTCxtRv3qfdvSbWCnWWj',
                'client_secret'=> 'lrFxI-iSEg-dckVUCl7Jqh8tq0OZYdD8hLwIjQQQj3vhUGYRlRFuAif5b3eGhCFYJNTGVxpZnE9ymr2_E4VaKrHRoylQF0e8LPWhmE_MR8M='
            ]
        ]);
        GetMapAccessToken::where('status',1)->update(['status'=> 0]);
        $response = $response->getBody()->getContents();
        $responseData = json_decode($response, true);
            DB::beginTransaction();
            $mapresponse = new GetMapAccessToken;
            $mapresponse->access_token = $responseData['access_token'];
            $mapresponse->token_type = $responseData['token_type'];
            $mapresponse->expires_in = $responseData['expires_in'];
            $mapresponse->project_code = $responseData['project_code'];
            $mapresponse->client_id = $responseData['client_id'];
            $mapresponse->status = 1;
            if($mapresponse->save()){
                    DB::commit();
                    return $responseData;
                }else{
                    DB::rollback();
                    $status = false;
                    return $status;
                }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }
    //route in web.php 
    function curlCreate(){

        
       // $url = "https://outpost.mapmyindia.com/api/security/oauth/token?grant_type=client_credentials&client_id=33OkryzDZsIv-0ItH_Z5UNahPSK5lioJCcwS1EHG-N4jV0GYSfP9wkajZyi5OJZpLFzje3em2cezmTCxtRv3qfdvSbWCnWWj&client_secret=lrFxI-iSEg-dckVUCl7Jqh8tq0OZYdD8hLwIjQQQj3vhUGYRlRFuAif5b3eGhCFYJNTGVxpZnE9ymr2_E4VaKrHRoylQF0e8LPWhmE_MR8M=";

        error_reporting(0);
        $client_id="33OkryzDZsIv-0ItH_Z5UNahPSK5lioJCcwS1EHG-N4jV0GYSfP9wkajZyi5OJZpLFzje3em2cezmTCxtRv3qfdvSbWCnWWj";
        $client_secret="lrFxI-iSEg-dckVUCl7Jqh8tq0OZYdD8hLwIjQQQj3vhUGYRlRFuAif5b3eGhCFYJNTGVxpZnE9ymr2_E4VaKrHRoylQF0e8LPWhmE_MR8M=";
        $token_url = "https://outpost.mapmyindia.com/api/security/oauth/token?grant_type=client_credentials";
        

        $curl_token = curl_init();
        curl_setopt($curl_token, CURLOPT_URL, $token_url);
        curl_setopt($curl_token, CURLOPT_POST, 1);
        curl_setopt($curl_token, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_token, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_token, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_token, CURLOPT_POSTFIELDS,
                    "client_id=".$client_id."&client_secret=".$client_secret);
        $result_token = curl_exec($curl_token);
        $responseData = json_decode($result_token, true);

        
        $err = curl_error($curl_token);
        curl_close($curl_token);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
        // print_r(json_decode($response));
        GetMapAccessToken::where('status',1)->update(['status'=> 0]);
        DB::beginTransaction();
            $mapresponse = new GetMapAccessToken;
            $mapresponse->access_token = $responseData['access_token'];
            $mapresponse->token_type = $responseData['token_type'];
            $mapresponse->expires_in = $responseData['expires_in'];
            $mapresponse->project_code = $responseData['project_code'];
            $mapresponse->client_id = $responseData['client_id'];
            $mapresponse->status = 1;
            if($mapresponse->save()){
                    DB::commit();
                    return $responseData;
                }else{
                    DB::rollback();
                    $status = false;
                    return $status;
                }
        }
    }
}
