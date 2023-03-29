<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Operator;
use DB;
use Log;
use File;

class replaceDuplicatedUIDs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:changeDuplicatedUID';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will change the same UID for two people';

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
        $duplicates = Operator::selectRaw("count('op_uid') as total, op_uid")
                   ->groupBy('op_uid')
                   ->where('op_uid', '!=', null)
                   ->get()->toArray();
       if(!empty($duplicates)){
        $not_verified_operator = $count = 0;
            foreach ($duplicates as $key => $value) {
                if($value['total'] <= 1){
                    unset($duplicates[$key]);
                }
                else{
                    $contents = null;
                    if(!empty($value['op_uid'])){
                        $records = Operator::select('op_user_id', 'op_dob', 'op_bu_pan_no', 'op_uid', 'op_mobile_no', 'op_is_verified')->where('op_uid', $value['op_uid'])->get()->toArray();
                        foreach ($records as $key1 => $value1) {
                            $getMaxOpID = null;
                            if($value1['op_is_verified'] == 1){
                                if($key1 == 0){
                                    continue;
                                }
                                else{
                                    $getMaxOpID = Operator::max('op_uid');
                                    $getMaxOpID++;
                                    $data = array('op_uid' => $getMaxOpID);
                                    $update = Operator::where('op_user_id', $value1['op_user_id'])->update($data);
                                    if($update  == 1){
                                        $contents .= "Old Details: ".' '.$value1['op_user_id'].' '.$value1['op_mobile_no'].' '.$value1['op_uid'];
                                        $contents .= " Updated Details: ".' '.$value1['op_user_id'].' '.$value1['op_mobile_no'].' '.$getMaxOpID."\n";
                                    }
                                    else{
                                        Log::warning("operator not updated: ".$value1['op_user_id']);
                                    }
                                }
                            }
                            else{
                                $not_verified_operator++;
                                Log::warning("operator not verified: ".$value1['op_user_id']);
                            }
                        }
                    }
                    else{
                        $count++;
                    }
                    $contents .= "Not verified op: ".' '.$not_verified_operator.' Null UIDS '.$count."\n";
                    File::put('storage/logs/changedUIDS'.date("G_a_m_d_y_h:i:sa").'.txt',$contents);
                }
            }
        }
    }
}
