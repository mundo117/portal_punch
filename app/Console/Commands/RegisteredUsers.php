<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use App\BioTimeUsersModel;
use Illuminate\Console\Command;
use Log;

class RegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new user and update in and out';

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
        $request = $client->get('http://demo.sysalwaysconnectedusa.com/api/v1/bioTime');
        $response = json_decode($request->getBody(),true);

      
        
        if($response){
            foreach($response as $data){
                $valuser = BioTimeUsersModel::where('badgenumber',$data['id'])->count(); 
                if($valuser == 0){
                    BioTimeUsersModel::insert([
                        'name'=> $data['name'],
                        'lastname'=>$data['last_name'], 
                        'nickname'=>$data['nick_name'],
                        'status'=> 0,
                        'badgenumber'=> $data['id'],
                        'app_status'=> 0,
                        'defaultdeptid'=>1,
                        'ATT'=>0,
                        'OverTime'=>0,
                        'Holiday'=>0,
                        'OffDuty'=>0,
                        'DelTag'=>0,
                        'set_valid_time'=>0,
                        'isatt'=>0,
                        'is_visitor'=>0,
                        ]);
                    }
                
            }
        }
    
        $user = BioTimeUsersModel::where('app_status',1)->count();
        echo 'Usuers On: '.$user;
            
    }
}
