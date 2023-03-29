<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\CustomAwsController;
use App\Http\Controllers\SendMailController;
use Carbon\Carbon;
use Config;
use Log;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DatabaseBackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Backup every 3 days';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bucketname = Config::get('custom_config_file.bucket-name');
        $path = config('custom_config_file.database_backup');
        if(!file_exists($path))
        {
            exec("mkdir ".$path);
        }

        $today_date = date('Y-m-d');
        $file_name = 'GGT_DB_'.$today_date.'.sql.gz';
        $mysqldumpfile = $path.$file_name;
        $database = config('custom_config_file.database');
        $dbuser = config('custom_config_file.dbuser');
        $dbpassword = config('custom_config_file.dbpassword');
        $dbhost = config('custom_config_file.dbhost');
        $awsPath = Config('custom_config_file.s3_path');

        // execute database backup command 
        // $export = exec("mysqldump -u$dbuser -h$dbhost -p$dbpassword $database | gzip > $mysqldumpfile");
        $export = shell_exec("mysqldump -u$dbuser -p$dbpassword $database | gzip > $mysqldumpfile");
        if(file_exists($mysqldumpfile))
        {   Log::warning("backup done please check");
            $uploadpath = $this->aws->uploadToS3($file_name,$mysqldumpfile,$bucketname);
            if($uploadpath){
                Log::warning("dbbackup uplaoded to s3");
            }else{
                Log::warning("failed to upload backup on s3");    
            }
        }else{
            Log::warning("No backup");
            $emailArray[] = 'madhuri@e-arth.in';
            $email_subject = 'Database Bakcup Fail';
            $email_body = '<p>Hello User,<br> This is to inform you that your GGT Database download and upload bakcup fail for the day';
            $send_email = $this->send_email->sendEmailTo($emailArray, $email_subject, $email_body);
        }
    }
}
