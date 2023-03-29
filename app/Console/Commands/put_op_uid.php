<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operator;

class put_op_uid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:put_op_uid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $verifiedOp = Operator::select('op_user_id', 'op_dob', 'op_bu_pan_no')->where('op_is_verified', 1)->where('op_uid', null)->get()->toArray();
        if(!empty($verifiedOp)){
            foreach ($verifiedOp as $key => $value) {
                $isAlreadyAPartern = Operator::where('op_bu_pan_no', $value['op_bu_pan_no'])->where('op_dob', $value['op_dob'])->where('op_uid', '!=' ,null)->exists();
                if($isAlreadyAPartern){
                    $old_uid = Operator::where('op_bu_pan_no', $value['op_bu_pan_no'])->where('op_dob', $value['op_dob'])->where('op_uid', '!=' ,null)->value('op_uid');
                    $data = array('op_uid' => $old_uid);
                }else{
                    $getMaxOpID = Operator::max('op_uid');
                    $getMaxOpID++;
                    $data = array('op_uid' => $getMaxOpID);
                }
                Operator::where('op_user_id', $value['op_user_id'])->update($data);
            }
        }
    }
}
