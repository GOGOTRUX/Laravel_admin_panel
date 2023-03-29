<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\GetMapAccessToken;
use DB;

class GetMapAcessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getMapAccessToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "https://outpost.mapmyindia.com/api/security/oauth/token?grant_type=client_credentials&client_id=33OkryzDZsIv-0ItH_Z5UNahPSK5lioJCcwS1EHG-N4jV0GYSfP9wkajZyi5OJZpLFzje3em2cezmTCxtRv3qfdvSbWCnWWj&client_secret=lrFxI-iSEg-dckVUCl7Jqh8tq0OZYdD8hLwIjQQQj3vhUGYRlRFuAif5b3eGhCFYJNTGVxpZnE9ymr2_E4VaKrHRoylQF0e8LPWhmE_MR8M=";

        $headers = array(  
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded",
            "Cache-Control: no-store",
            );
        $data = [
            'grant_type' => 'client_credentials',
            ];
            
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url ,  
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $responseData = json_decode($response, true);
        // dd($responseData);
        $err = curl_error($curl);
        curl_close($curl);

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
                    Log::info('Successfull');
                    return $responseData;
                }else{
                    DB::rollback();
                    $status = false;
                    Log::info('Error ');
                    return $status;
                }
        }
    }
}

