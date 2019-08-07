<?php
namespace App\Controllers\MCC;
use App\Models\MCC as MCC;

class MCCController  {

    public function getWorkPackList($request, $response, $args) {
        try
        {        
            $page  = isset($args['page']) ? (int) $args['page'] : 1;
            $limit = isset($args['limit']) ? (int) $args['limit'] : 10;
            $count = MCC::count(); 
            if($count > 0)
            {
                $MCC = MCC::skip(($page - 1) * $limit)->take($limit)->get();  
                return $response->withJson(['sucess'=> true,'cnt' => $count,'data' => $MCC]);         
            }
            else
            {
                return $response->withJson(['sucess'=> true,'msg' => 'No Data Found']);         
            }
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }

    public function getWorkPack($request, $response, $args) {
        try
        {        
            $id  = isset($args['id']) ? (int) $args['id'] : 0;          
            if($id > 0)
            {
                $MCC = MCC::find($id); 
                return $response->withJson(['sucess'=> true,'data' => $MCC]);         
            }
            else
            {
                return $response->withJson(['sucess'=> true,'msg' => 'No Data Found']);         
            }
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }

    public function CreateWorkPack($request, $response, $args) {
        try
        {        
            $data = $request->getParsedBody();         
            $MCC =  new MCC; 
            $MCC->check_name = $data['checkName'];
            $MCC->save(); 
            return $response->withJson(['sucess'=> true,'msg' => 'Added Sucessfully']);   
        }catch (Error $e) {
            return $response->withJson(['error'=> true,'msg' => $e]);
         }                 
    }
        
}