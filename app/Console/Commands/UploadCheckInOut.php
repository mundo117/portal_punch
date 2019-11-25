<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CheckInOutModel;
class UploadCheckInOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:CheckInOut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload data check in or check out';

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
        $clientV1= new \GuzzleHttp\Client();
        $requestV1 = $clientV1->get('http://demo.sysalwaysconnectedusa.com/api/v1/bioTime/checks');
        $responseV1 = json_decode($requestV1->getBody(),true); 
        
        if($responseV1){
               $check = CheckInOutModel::select('userid','checktime','upload_time')->where('userid','>',$responseV1['id'])->get();
        }else{
            $check = CheckInOutModel::select('userid','checktime','upload_time')->get();
        }
       
        $clientV2 = new \GuzzleHttp\Client();
        $headers['Content-Type'] = 'application/json';
        $chekdata = json_encode($check);
        $responseV2 = $clientV2->request('POST', 'http://demo.sysalwaysconnectedusa.com/api/v1/bioTime/store', array('headers' => $headers,'body' =>$chekdata));
        $responseV2 = json_decode($responseV2->getBody(),true);
       
        echo 'Checks ready';
    }
}
