<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $client = new \GuzzleHttp\Client();
        $check = CheckInOutModel::select('userid','checktime','upload_time')->get();
        $headers['Content-Type'] = 'application/json';
        $chekdata = json_encode($check);
        $response = $client->request('POST', 'http://192.168.1.141:8000/api/v1/bioTime/store', array('headers' => $headers,'body' =>$chekdata));
    }
}
