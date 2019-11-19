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
        $request = $client->get('http://192.168.1.141:8000/api/v1/bioTime');
        $response = json_decode($request->getBody(),true);

        foreach($response as $data){
           
            if($data['employee']['id'] > 1){
            BioTimeUsersModel::insert([
                'name'=> $data['employee']['name'],
                'lastname'=>$data['employee']['last_name'], 
                'nickname'=>$data['employee']['nick_name'],
                'status'=> 0,
                'badgenumber'=> $data['employee']['id'],
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
        $request = $client->get('http://192.168.1.141:8000/api/v1/bioTime/delete_users');
        $response = json_decode($request->getBody(),true);
        $rows=count($response);

        for($i = 0; $i < $rows; $i++){
            $var = BioTimeUsersModel::where('badgenumber',$response[$i]['id'])->first(); 
            $var->app_status = 0;
            $var->save();
        }

        $var2 = BioTimeUsersModel::where('app_status',0)->get();
        return response()->json($var2);
    }


    public function inserttime(){
        
        $clientV1= new \GuzzleHttp\Client();
        $requestV1 = $clientV1->get('http://192.168.1.141:8000/api/v1/bioTime/checks');
        $responseV1 = json_decode($requestV1->getBody(),true); 



        $clientV2 = new \GuzzleHttp\Client();
        $check = CheckInOutModel::select('userid','checktime','upload_time')->where('id',$responseV1['id'])->get();
        
        $headers['Content-Type'] = 'application/json';
        $chekdata = json_encode($check);
        $responseV2 = $clientV2->request('POST', 'http://192.168.1.141:8000/api/v1/bioTime/store', array('headers' => $headers,'body' =>$chekdata));
        $responseV2 = json_decode($responseV2->getBody(),true);
        return response()->json($responseV2);
    
    }
}