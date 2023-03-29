<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OperatorAccounts;
use App\Models\Operator;

class updateUIDInOperatorAccountTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateUIDInOperatorAccount';

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
        $emptyUID = $updated = $failed = 0;
        $getEmptyUIDEntries = OperatorAccounts::select('op_uid', 'op_mobile_no', 'account_id')->where('op_uid', null)->get()->toArray();
        if(!empty($getEmptyUIDEntries)){
            foreach ($getEmptyUIDEntries as $key => $value) {
                $op_uid = Operator::where('op_mobile_no', $value['op_mobile_no'])->value('op_uid');
                if(!empty($op_uid)){
                    $update = OperatorAccounts::where('op_mobile_no', $value['op_mobile_no'])->update(['op_uid'=> $op_uid]);
                    if($update == 1){
                        $updated++;
                    }
                    else{
                        $failed++;
                    }
                }
                else{
                    $emptyUID++;
                }
            }
        }
        echo "Successfully Updated Records: ".$updated."\n";
        echo "Failed Records: ".$failed."\n";
        echo "Found Empty UID: ".$emptyUID."\n";
    }
}
