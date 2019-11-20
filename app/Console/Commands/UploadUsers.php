<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:UploadUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload data users';

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
        $request = $client->get('http://192.168.1.141:8000/api/v1/bioTime/delete_users');
        $response = json_decode($request->getBody(),true);
        $rows=count($response);

        for($i = 0; $i < $rows; $i++){
            $var = BioTimeUsersModel::where('badgenumber',$response[$i]['id'])->first(); 
            $var->app_status = 0;
            $var->save();
        }
    }
}
