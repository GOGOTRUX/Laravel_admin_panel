<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\SendMailController;
use Carbon\Carbon;
use Config;
use Log;
use App\Models\Subscriptionplan;

class activateSubplans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:activateSubplans';

    /**
     * The console command description.
     *
     * @var string
    */
    protected $description = 'Run daily to at 12';

    /**
     * Create a new command instance.
     *
     * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->aws = new CustomAwsController;        
        $this->send_email = new SendMailController;        
        $this->todays = Carbon::today()->toDateString();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
    */
    public function handle()
    {
        $subplan = Subscriptionplan::select('subscription_id','subscription_type_name','subscription_amount','subscription_validity_type','subscription_business_rs','subscription_expected_enquiries','subscription_veh_wheel_type','subscription_validity_days','subscription_validity_from','subscription_validity_to','is_free_trial','is_active','is_sent_for_approval','is_approved','is_approved_by','subscription_plan_created_by')->orderBy('subscription_id', 'desc')->get()->toArray();
        
        if (!empty($subplan)) {
            foreach ($subplan as $key => $value) {
                $to = $value['subscription_validity_to'];
                $from = $value['subscription_validity_from'];
                if($from == $this->todays){ //plan match then update status
                    $affectedRows = Subscriptionplan::where('subscription_id',$value['subscription_id'])->where('is_approved',1)->update(array('is_active' => 1));
                    Log::warning('plans is active,updated using crons');
                }else{
                    Log::warning('no plans available,command:activateSubplans ');
                }
            }    
        }else{
            Log::warning('plans is empty,command:activateSubplans ');
        }
    }
}
