<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BioTimeUsersModel;
use App\CheckInOutModel;
class BioTimeUsersController extends Controller
{
    public function index(){
        
        $user = BioTimeUsersModel::select('name','lastname', 'nickname')->get();

        return response()->json(json_encode($user));
      
    }

    public function store(){


        $client = new \GuzzleHttp\Client();
        $request = $client->get('http://demo.sysalwaysconnectedusa.com/api/v1/bioTime');
        $response = json_decode($request->getBody(),true);

        if($response){
            foreach($response as $data){
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
    
        $user = BioTimeUsersModel::all();
        return response()->json($user);
    }

    public function backuptime(Request $request){

        $client = new \GuzzleHttp\Client();
        $request = $client->get('http://demo.sysalwaysconnectedusa.com/api/v1/bioTime/delete_users');
        $response = json_decode($request->getBody(),true);
  
        if($response){
            $rows=count($response);

            for($i = 0; $i < $rows; $i++){
                $var = BioTimeUsersModel::where('badgenumber',$response[$i]['id'])->first(); 
               
                    $var ->app_status = 4;
                    $var->save();
                echo $response[$i]['id'].',';
            }

            $var2 = BioTimeUsersModel::where('app_status',4)->count();
            return response()->json($var2);
        }
        return response()->json('Error');
    }


    public function inserttime(){
        
        $clientV1= new \GuzzleHttp\Client();
        $requestV1 = $clientV1->get('http://demo.sysalwaysconnectedusa.com/api/v1/bioTime/checks');
        $responseV1 = json_decode($requestV1->getBody(),true); 
        
        if(is_null($responseV1)){
               $check = CheckInOutModel::select('userid','checktime','upload_time')->where('userid',$responseV1['id'])->get();
        }else{
            $check = CheckInOutModel::select('userid','checktime','upload_time')->get();
        }
       
        $clientV2 = new \GuzzleHttp\Client();
        $headers['Content-Type'] = 'application/json';
        $chekdata = json_encode($check);
        $responseV2 = $clientV2->request('POST', 'http://demo.sysalwaysconnectedusa.com/api/v1/bioTime/store', array('headers' => $headers,'body' =>$chekdata));
        $responseV2 = json_decode($responseV2->getBody(),true);
        return response()->json($responseV2);
    }
}