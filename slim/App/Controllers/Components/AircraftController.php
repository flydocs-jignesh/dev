<?php
namespace App\Controllers\Components;
use App\Models\Aircraft as Aircraft;

class AircraftController  {

    public function index($request, $response, $args) {
        try
        {    
            $data = $request->getParsedBody();
            $page  = $data['page'] ? $data['page'] : 1;
            $limit = $data['limit'] ? $data['limit'] : 10;
            $count = Aircraft::where('CLIENTID', '=', $data['clientID'])->count();
            if($count > 0)
            {
                $Aircrafts = Aircraft::where('CLIENTID', '=', $data['clientID'])->skip(($page - 1) * $limit)->take($limit)->get();  
                return $response->withJson(['sucess'=> true,'cnt' => $count,'data' => $Aircrafts]);         
            }
            else
            {
                return $response->withJson(['sucess'=> true,'msg' => 'No Data Found']);         
            }
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }
        
}