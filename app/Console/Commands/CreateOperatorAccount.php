<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Config;
use Log;
use App\Models\Operator;
use App\Models\OperatorAccounts;

class CreateOperatorAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreateOperatorAccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Only one time run command : to create existing operator account entry in ggt_operator_accounts table';

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
        $getOperators = Operator::select('op_user_id','op_mobile_no','op_uid')->get()->toArray();
        if(!empty($getOperators)){
            foreach ($getOperators as $key => $value) {
                $isAccountExist = OperatorAccounts::where('op_user_id',$value['op_user_id'])->exists();
                if(!$isAccountExist){
                    $op_account = new OperatorAccounts();
                    $op_account->op_user_id = $value['op_user_id'];
                    $op_account->op_uid = (!empty($value['op_uid'])) ? $value['op_uid'] : null ; 
                    $op_account->op_mobile_no = $value['op_mobile_no'];
                    $op_account->save();
                    if(!empty($op_account->account_id)){
                        Log::notice('account created for op'.$value['op_user_id']);
                    }else{
                        Log::error('error in op account create, command:CreateOperatorAccount'.$value['op_user_id']);
                    }
                    Log::warning('account does not exist,create account of operator id'.$value['op_user_id']);
                }else{
                    // Log::warning('account exist');
                }
            }
        }
    }
}
